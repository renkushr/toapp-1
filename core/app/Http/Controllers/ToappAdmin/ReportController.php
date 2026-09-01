<?php

namespace App\Http\Controllers\ToappAdmin;

use App\Constants\Status;
use App\Models\AdminAuditLog;
use App\Models\Investment;
use App\Models\Transaction;
use App\Models\UserLogin;
use App\Models\Agent;
use App\Services\AgentReportService;
use App\Services\ReferralCommissionService;
use App\Support\AdminAudit;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReportController extends Controller
{
    public function transactions(Request $request)
    {
        $remarks = Transaction::query()
            ->whereNotNull('remark')
            ->distinct()
            ->orderBy('remark')
            ->pluck('remark');

        $transactions = $this->transactionQuery($request)
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('toapp_admin.reports.transactions', [
            'pageTitle' => 'Transaction Reports',
            'transactions' => $transactions,
            'remarks' => $remarks,
        ]);
    }

    public function transactionsExport(Request $request)
    {
        $transactions = $this->transactionQuery($request)->latest()->get();

        return response()->streamDownload(function () use ($transactions) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['TRX', 'User', 'Email', 'Type', 'Amount', 'Charge', 'Post Balance', 'Remark', 'Details', 'Date']);

            foreach ($transactions as $trx) {
                fputcsv($handle, [
                    $trx->trx,
                    optional($trx->user)->username,
                    optional($trx->user)->email,
                    $trx->trx_type,
                    $trx->amount,
                    $trx->charge,
                    $trx->post_balance,
                    $trx->remark,
                    $trx->details,
                    optional($trx->created_at)->toDateTimeString(),
                ]);
            }

            fclose($handle);
        }, 'transactions-' . now()->format('Ymd-His') . '.csv', ['Content-Type' => 'text/csv']);
    }

    protected function transactionQuery(Request $request)
    {
        return Transaction::with('user')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($inner) use ($search) {
                    $inner->where('trx', 'like', "%{$search}%")
                        ->orWhere('details', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($user) => $user->where('username', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('trx_type'), fn ($query) => $query->where('trx_type', $request->trx_type))
            ->when($request->filled('remark'), fn ($query) => $query->where('remark', $request->remark));
    }

    public function investments(Request $request)
    {
        $investments = $this->investmentQuery($request)
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('toapp_admin.reports.investments', [
            'pageTitle' => 'Investment Reports',
            'investments' => $investments,
        ]);
    }

    public function showInvestment(Investment $investment)
    {
        $investment->load(['user', 'plan']);

        return view('toapp_admin.reports.investment_show', [
            'pageTitle' => 'Review Investment',
            'investment' => $investment,
        ]);
    }

    public function investmentsExport(Request $request)
    {
        $investments = $this->investmentQuery($request)->latest()->get();

        return response()->streamDownload(function () use ($investments) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['TRX', 'User', 'Email', 'Plan', 'Amount', 'Interest', 'Total Return', 'Paid', 'Status', 'Approved At', 'Rejected At', 'Reject Reason', 'Date']);

            foreach ($investments as $investment) {
                fputcsv($handle, [
                    $investment->trx,
                    optional($investment->user)->username,
                    optional($investment->user)->email,
                    optional($investment->plan)->name,
                    $investment->amount,
                    $investment->interest_amount,
                    $investment->total_return,
                    $investment->total_paid,
                    $this->investmentStatusLabel((int) $investment->status),
                    optional($investment->approved_at)->toDateTimeString(),
                    optional($investment->rejected_at)->toDateTimeString(),
                    $investment->rejection_reason,
                    optional($investment->created_at)->toDateTimeString(),
                ]);
            }

            fclose($handle);
        }, 'investments-' . now()->format('Ymd-His') . '.csv', ['Content-Type' => 'text/csv']);
    }

    public function approveInvestment(Investment $investment)
    {
        abort_unless($investment->status == Status::INVESTMENT_PENDING, 422);

        $commissionCount = DB::transaction(function () use ($investment) {
            $investment->status = Status::RUNNING;
            $investment->approved_at = now();
            $investment->approved_by = auth('admin')->id();
            $investment->rejected_at = null;
            $investment->rejection_reason = null;
            $investment->save();

            return app(ReferralCommissionService::class)->payForInvestment($investment);
        });

        AdminAudit::record('investment.approved', $investment, [
            'trx' => $investment->trx,
            'amount' => $investment->amount,
            'commission_levels_paid' => $commissionCount,
        ]);

        return redirect()
            ->route('toapp.admin.reports.investments.show', $investment)
            ->with('status', "Investment approved. {$commissionCount} referral fee level(s) paid.");
    }

    public function rejectInvestment(Request $request, Investment $investment)
    {
        abort_unless($investment->status == Status::INVESTMENT_PENDING, 422);

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($investment, $validated) {
            $investment->status = Status::INVESTMENT_REJECTED;
            $investment->rejected_at = now();
            $investment->rejection_reason = $validated['reason'];
            $investment->save();

            $user = $investment->user;
            $user->balance += $investment->amount;
            $user->save();

            $transaction = new Transaction();
            $transaction->user_id = $investment->user_id;
            $transaction->amount = $investment->amount;
            $transaction->post_balance = $user->balance;
            $transaction->charge = 0;
            $transaction->trx_type = '+';
            $transaction->remark = 'invest_reject';
            $transaction->details = 'Refunded package purchase request: ' . $validated['reason'];
            $transaction->trx = $investment->trx;
            $transaction->save();
        });

        AdminAudit::record('investment.rejected', $investment, [
            'trx' => $investment->trx,
            'amount' => $investment->amount,
            'reason' => $validated['reason'],
        ]);

        return redirect()
            ->route('toapp.admin.reports.investments.show', $investment)
            ->with('status', 'Investment rejected and balance refunded.');
    }

    protected function investmentQuery(Request $request)
    {
        return Investment::with(['user', 'plan'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($inner) use ($search) {
                    $inner->where('trx', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($user) => $user->where('username', 'like', "%{$search}%"))
                        ->orWhereHas('plan', fn ($plan) => $plan->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->integer('status')));
    }

    public function logins(Request $request)
    {
        $logins = $this->loginQuery($request)
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('toapp_admin.reports.logins', [
            'pageTitle' => 'Login Reports',
            'logins' => $logins,
        ]);
    }

    public function loginsExport(Request $request)
    {
        $logins = $this->loginQuery($request)->latest()->get();

        return response()->streamDownload(function () use ($logins) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['User', 'Email', 'IP', 'City', 'Country', 'Country Code', 'Browser', 'OS', 'Date']);

            foreach ($logins as $login) {
                fputcsv($handle, [
                    optional($login->user)->username,
                    optional($login->user)->email,
                    $login->user_ip,
                    $login->city,
                    $login->country,
                    $login->country_code,
                    $login->browser,
                    $login->os,
                    optional($login->created_at)->toDateTimeString(),
                ]);
            }

            fclose($handle);
        }, 'login-history-' . now()->format('Ymd-His') . '.csv', ['Content-Type' => 'text/csv']);
    }

    protected function loginQuery(Request $request)
    {
        return UserLogin::with('user')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($inner) use ($search) {
                    $inner->where('user_ip', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('country', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($user) => $user->where('username', 'like', "%{$search}%"));
                });
            });
    }

    public function agents(Request $request, AgentReportService $reports)
    {
        $days = max(0, (int) $request->input('days', 30));
        $since = $reports->periodStart($days);
        $rows = $reports->summariesForAllAgents($since, $request->string('search')->toString() ?: null);

        return view('toapp_admin.reports.agents', [
            'pageTitle' => 'Agent Reports',
            'rows' => $rows,
            'totals' => $reports->aggregateTotals($rows),
            'days' => $days,
        ]);
    }

    public function showAgent(Request $request, Agent $agent, AgentReportService $reports)
    {
        $days = max(0, (int) $request->input('days', 30));
        $since = $reports->periodStart($days);

        return view('toapp_admin.reports.agent_show', [
            'pageTitle' => 'Agent Report: ' . $agent->name,
            'agent' => $agent,
            'metrics' => $reports->summaryForAgent($agent, $since),
            'days' => $days,
        ]);
    }

    public function agentsExport(Request $request, AgentReportService $reports)
    {
        $days = max(0, (int) $request->input('days', 30));
        $since = $reports->periodStart($days);
        $rows = $reports->summariesForAllAgents($since, $request->string('search')->toString() ?: null);

        return response()->streamDownload(function () use ($rows, $days) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Agent',
                'License',
                'Email',
                'Status',
                'Clients',
                'Active Clients',
                'Approved Clients',
                'Active Products',
                'Investment Sales Count',
                'Investment Sales Amount',
                'Investment Pending Count',
                'Investment Pending Amount',
                'Insurance Confirmed Count',
                'Insurance Confirmed Annual',
                'Agent Fee %',
                'Platform Fee Amount',
                'Insurance Pipeline',
                'Client Logins',
                'Chat Messages (Client)',
                'Chat Messages (Agent)',
                'Conversations',
                'Period Days',
            ]);

            foreach ($rows as $row) {
                $agent = $row['agent'];
                $metrics = $row['metrics'];

                fputcsv($handle, [
                    $agent->name,
                    $agent->license_no,
                    $agent->email,
                    $agent->status ? 'Active' : 'Inactive',
                    $metrics['clients_total'],
                    $metrics['clients_active'],
                    $metrics['clients_approved'],
                    $metrics['active_products'],
                    $metrics['investment_sales_count'],
                    $metrics['investment_sales_amount'],
                    $metrics['investment_pending_count'],
                    $metrics['investment_pending_amount'],
                    $metrics['insurance_confirmed_count'],
                    $metrics['insurance_confirmed_annual'],
                    $metrics['agent_fee_percent'],
                    $metrics['platform_fee_amount'],
                    $metrics['insurance_pipeline_count'],
                    $metrics['logins_count'],
                    $metrics['chat_messages_user'],
                    $metrics['chat_messages_agent'],
                    $metrics['conversations_count'],
                    $days,
                ]);
            }

            fclose($handle);
        }, 'agent-reports-' . now()->format('Ymd-His') . '.csv', ['Content-Type' => 'text/csv']);
    }

    public function audits(Request $request)
    {
        $audits = AdminAuditLog::with('admin')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($inner) use ($search) {
                    $inner->where('action', 'like', "%{$search}%")
                        ->orWhere('target_type', 'like', "%{$search}%")
                        ->orWhere('ip_address', 'like', "%{$search}%")
                        ->orWhereHas('admin', fn ($admin) => $admin->where('username', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('toapp_admin.reports.audits', [
            'pageTitle' => 'Admin Audit Logs',
            'audits' => $audits,
        ]);
    }

    protected function investmentStatusLabel(int $status): string
    {
        return match ($status) {
            Status::INVESTMENT_PENDING => 'Pending',
            Status::RUNNING => 'Running',
            Status::COMPLETED => 'Completed',
            Status::INVESTMENT_REJECTED => 'Rejected',
            default => 'Unknown',
        };
    }
}
