<?php

namespace App\Http\Controllers\ToappAdmin;

use App\Constants\Status;
use App\Models\Transaction;
use App\Models\Withdrawal;
use App\Support\AdminAudit;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $withdrawals = $this->withdrawalQuery($request)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('toapp_admin.withdrawals.index', [
            'pageTitle' => __('Withdrawals'),
            'withdrawals' => $withdrawals,
        ]);
    }

    public function export(Request $request)
    {
        $withdrawals = $this->withdrawalQuery($request)->latest()->get();

        return response()->streamDownload(function () use ($withdrawals) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['TRX', 'User', 'Email', 'Amount', 'Charge', 'Final Amount', 'Currency', 'Method', 'Status', 'Admin Feedback', 'Date']);

            foreach ($withdrawals as $withdrawal) {
                fputcsv($handle, [
                    $withdrawal->trx,
                    optional($withdrawal->user)->username,
                    optional($withdrawal->user)->email,
                    $withdrawal->amount,
                    $withdrawal->charge,
                    $withdrawal->final_amount,
                    $withdrawal->currency,
                    optional($withdrawal->method)->name,
                    [1 => 'Approved', 2 => 'Pending', 3 => 'Rejected'][$withdrawal->status] ?? $withdrawal->status,
                    $withdrawal->admin_feedback,
                    optional($withdrawal->created_at)->toDateTimeString(),
                ]);
            }

            fclose($handle);
        }, 'withdrawals-' . now()->format('Ymd-His') . '.csv', ['Content-Type' => 'text/csv']);
    }

    protected function withdrawalQuery(Request $request)
    {
        return Withdrawal::with(['user', 'method'])
            ->where('status', '!=', Status::PAYMENT_INITIATE)
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($inner) use ($search) {
                    $inner->where('trx', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($user) => $user->where('username', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->integer('status')));
    }

    public function show(Withdrawal $withdrawal)
    {
        $withdrawal->load(['user', 'method']);

        return view('toapp_admin.withdrawals.show', [
            'pageTitle' => __('Withdrawal Detail'),
            'withdrawal' => $withdrawal,
        ]);
    }

    public function approve(Request $request, Withdrawal $withdrawal)
    {
        abort_unless($withdrawal->status == Status::PAYMENT_PENDING, 422);

        $validated = $request->validate([
            'details' => ['nullable', 'string', 'max:1000'],
        ]);

        $withdrawal->status = Status::PAYMENT_SUCCESS;
        $withdrawal->admin_feedback = $validated['details'] ?? null;
        $withdrawal->save();

        AdminAudit::record('withdrawal.approved', $withdrawal, [
            'trx' => $withdrawal->trx,
            'amount' => $withdrawal->amount,
            'user_id' => $withdrawal->user_id,
            'details' => $validated['details'] ?? null,
        ]);

        notify($withdrawal->user, 'WITHDRAW_APPROVE', [
            'method_name' => $withdrawal->method?->name ?? 'Withdrawal method',
            'method_currency' => $withdrawal->currency,
            'method_amount' => showAmount($withdrawal->final_amount, currencyFormat: false),
            'amount' => showAmount($withdrawal->amount, currencyFormat: false),
            'charge' => showAmount($withdrawal->charge, currencyFormat: false),
            'rate' => showAmount($withdrawal->rate, currencyFormat: false),
            'trx' => $withdrawal->trx,
            'admin_details' => $validated['details'] ?? '',
        ]);

        return back()->with('status', 'Withdrawal approved successfully.');
    }

    public function reject(Request $request, Withdrawal $withdrawal)
    {
        abort_unless($withdrawal->status == Status::PAYMENT_PENDING, 422);

        $validated = $request->validate([
            'details' => ['required', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($withdrawal, $validated) {
            $withdrawal->status = Status::PAYMENT_REJECT;
            $withdrawal->admin_feedback = $validated['details'];
            $withdrawal->save();

            $user = $withdrawal->user;
            $user->balance += $withdrawal->amount;
            $user->save();

            $transaction = new Transaction();
            $transaction->user_id = $withdrawal->user_id;
            $transaction->amount = $withdrawal->amount;
            $transaction->post_balance = $user->balance;
            $transaction->charge = 0;
            $transaction->trx_type = '+';
            $transaction->remark = 'withdraw_reject';
            $transaction->details = 'Refunded for withdrawal rejection';
            $transaction->trx = $withdrawal->trx;
            $transaction->save();
        });

        $withdrawal->loadMissing(['user', 'method']);

        AdminAudit::record('withdrawal.rejected', $withdrawal, [
            'trx' => $withdrawal->trx,
            'amount' => $withdrawal->amount,
            'user_id' => $withdrawal->user_id,
            'reason' => $validated['details'],
        ]);

        notify($withdrawal->user, 'WITHDRAW_REJECT', [
            'method_name' => $withdrawal->method?->name ?? 'Withdrawal method',
            'method_currency' => $withdrawal->currency,
            'method_amount' => showAmount($withdrawal->final_amount, currencyFormat: false),
            'amount' => showAmount($withdrawal->amount, currencyFormat: false),
            'charge' => showAmount($withdrawal->charge, currencyFormat: false),
            'rate' => showAmount($withdrawal->rate, currencyFormat: false),
            'trx' => $withdrawal->trx,
            'post_balance' => showAmount($withdrawal->user->balance, currencyFormat: false),
            'admin_details' => $validated['details'],
        ]);

        return back()->with('status', 'Withdrawal rejected and balance refunded.');
    }
}
