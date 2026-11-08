<?php

namespace App\Http\Controllers\ToappAdmin;

use App\Constants\Status;
use App\Http\Controllers\Gateway\PaymentController;
use App\Models\Deposit;
use App\Support\AdminAudit;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DepositController extends Controller
{
    public function index(Request $request)
    {
        $deposits = $this->depositQuery($request)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('toapp_admin.deposits.index', [
            'pageTitle' => __('Deposits'),
            'deposits' => $deposits,
        ]);
    }

    public function export(Request $request)
    {
        $deposits = $this->depositQuery($request)->latest()->get();

        return response()->streamDownload(function () use ($deposits) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['TRX', 'User', 'Email', 'Amount', 'Charge', 'Final Amount', 'Method', 'Currency', 'Status', 'Admin Feedback', 'Date']);

            foreach ($deposits as $deposit) {
                fputcsv($handle, [
                    $deposit->trx,
                    optional($deposit->user)->username,
                    optional($deposit->user)->email,
                    $deposit->amount,
                    $deposit->charge,
                    $deposit->final_amount,
                    optional($deposit->gateway)->name ?? $deposit->method_currency,
                    $deposit->method_currency,
                    ['Initiated','Success','Pending','Rejected'][$deposit->status] ?? $deposit->status,
                    $deposit->admin_feedback,
                    optional($deposit->created_at)->toDateTimeString(),
                ]);
            }

            fclose($handle);
        }, 'deposits-' . now()->format('Ymd-His') . '.csv', ['Content-Type' => 'text/csv']);
    }

    protected function depositQuery(Request $request)
    {
        return Deposit::with(['user', 'gateway'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($inner) use ($search) {
                    $inner->where('trx', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($user) => $user->where('username', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->integer('status')));
    }

    public function show(Deposit $deposit)
    {
        $deposit->load(['user', 'gateway']);

        return view('toapp_admin.deposits.show', [
            'pageTitle' => __('Deposit Detail'),
            'deposit' => $deposit,
        ]);
    }

    public function approve(Deposit $deposit)
    {
        abort_unless($deposit->status == Status::PAYMENT_PENDING, 422);

        PaymentController::userDataUpdate($deposit, true);

        AdminAudit::record('deposit.approved', $deposit, [
            'trx' => $deposit->trx,
            'amount' => $deposit->amount,
            'user_id' => $deposit->user_id,
        ]);

        return back()->with('status', 'Deposit approved successfully.');
    }

    public function reject(Request $request, Deposit $deposit)
    {
        abort_unless($deposit->status == Status::PAYMENT_PENDING, 422);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:255'],
        ]);

        $deposit->admin_feedback = $validated['message'];
        $deposit->status = Status::PAYMENT_REJECT;
        $deposit->save();

        AdminAudit::record('deposit.rejected', $deposit, [
            'trx' => $deposit->trx,
            'amount' => $deposit->amount,
            'user_id' => $deposit->user_id,
            'reason' => $validated['message'],
        ]);

        notify($deposit->user, 'DEPOSIT_REJECT', [
            'method_name' => $deposit->methodName(),
            'method_currency' => $deposit->method_currency,
            'method_amount' => showAmount($deposit->final_amount, currencyFormat: false),
            'amount' => showAmount($deposit->amount, currencyFormat: false),
            'charge' => showAmount($deposit->charge, currencyFormat: false),
            'rate' => showAmount($deposit->rate, currencyFormat: false),
            'trx' => $deposit->trx,
            'rejection_message' => $validated['message'],
        ]);

        return back()->with('status', 'Deposit rejected successfully.');
    }
}
