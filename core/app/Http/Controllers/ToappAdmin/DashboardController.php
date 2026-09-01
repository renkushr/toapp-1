<?php

namespace App\Http\Controllers\ToappAdmin;

use App\Constants\Status;
use App\Models\Deposit;
use App\Models\Investment;
use App\Models\Plan;
use App\Models\ReferralCommission;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'pending_users' => User::approvalPending()->count(),
            'unassigned_users' => User::withoutAgent()->count(),
            'active_users' => User::active()->count(),
            'plans' => Plan::count(),
            'pending_investments' => Investment::where('status', Status::INVESTMENT_PENDING)->count(),
            'running_investments' => Investment::where('status', Status::RUNNING)->count(),
            'pending_deposits' => Deposit::pending()->count(),
            'pending_withdrawals' => Withdrawal::pending()->count(),
            'total_deposited' => Deposit::successful()->sum('amount'),
            'total_withdrawn' => Withdrawal::approved()->sum('amount'),
            'referral_paid' => ReferralCommission::sum('amount'),
        ];

        $recentUsers = User::latest()->take(6)->get();
        $recentTransactions = Transaction::with('user')->latest()->take(6)->get();
        $recentDeposits = Deposit::with('user')->latest()->take(5)->get();
        $recentWithdrawals = Withdrawal::with(['user', 'method'])->latest()->take(5)->get();

        return view('toapp_admin.dashboard', [
            'pageTitle' => 'Control Center',
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'recentTransactions' => $recentTransactions,
            'recentDeposits' => $recentDeposits,
            'recentWithdrawals' => $recentWithdrawals,
        ]);
    }
}
