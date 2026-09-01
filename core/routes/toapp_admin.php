<?php

use App\Http\Controllers\ToappAdmin\AgentController;
use App\Http\Controllers\ToappAdmin\AuthController;
use App\Http\Controllers\ToappAdmin\AdminUserController;
use App\Http\Controllers\ToappAdmin\DashboardController;
use App\Http\Controllers\ToappAdmin\InsuranceApplicationController;
use App\Http\Controllers\ToappAdmin\DepositController;
use App\Http\Controllers\ToappAdmin\MethodController;
use App\Http\Controllers\ToappAdmin\PlanController;
use App\Http\Controllers\ToappAdmin\ReadinessController;
use App\Http\Controllers\ToappAdmin\ReferralController;
use App\Http\Controllers\ToappAdmin\ReportController;
use App\Http\Controllers\ToappAdmin\SettingController;
use App\Http\Controllers\ToappAdmin\SupportController;
use App\Http\Controllers\ToappAdmin\UserController;
use App\Http\Controllers\ToappAdmin\WithdrawalController;
use Illuminate\Support\Facades\Route;

Route::middleware('toapp.admin.guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('toapp.admin')->name('logout');

Route::middleware('toapp.admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('toapp.admin.can:dashboard')->name('dashboard');

    Route::get('/admins', [AdminUserController::class, 'index'])->middleware('toapp.admin.can:admins')->name('admins.index');
    Route::get('/admins/create', [AdminUserController::class, 'create'])->middleware('toapp.admin.can:admins')->name('admins.create');
    Route::post('/admins', [AdminUserController::class, 'store'])->middleware('toapp.admin.can:admins')->name('admins.store');
    Route::get('/admins/{admin}/edit', [AdminUserController::class, 'edit'])->middleware('toapp.admin.can:admins')->name('admins.edit');
    Route::put('/admins/{admin}', [AdminUserController::class, 'update'])->middleware('toapp.admin.can:admins')->name('admins.update');

    Route::get('/users', [UserController::class, 'index'])->middleware('toapp.admin.can:users')->name('users.index');
    Route::get('/users/export', [UserController::class, 'export'])->middleware('toapp.admin.can:users')->name('users.export');
    Route::get('/users/{user}', [UserController::class, 'show'])->middleware('toapp.admin.can:users')->name('users.show');
    Route::post('/users/{user}/approve', [UserController::class, 'approve'])->middleware('toapp.admin.can:users')->name('users.approve');
    Route::post('/users/{user}/reject', [UserController::class, 'reject'])->middleware('toapp.admin.can:users')->name('users.reject');
    Route::post('/users/{user}/status', [UserController::class, 'status'])->middleware('toapp.admin.can:users')->name('users.status');
    Route::post('/users/{user}/verification', [UserController::class, 'verification'])->middleware('toapp.admin.can:users')->name('users.verification');
    Route::post('/users/{user}/balance', [UserController::class, 'balance'])->middleware('toapp.admin.can:balances')->name('users.balance');
    Route::post('/users/{user}/agent', [UserController::class, 'assignAgent'])->middleware('toapp.admin.can:users')->name('users.agent');

    Route::get('/plans', [PlanController::class, 'index'])->middleware('toapp.admin.can:plans')->name('plans.index');
    Route::get('/plans/create', [PlanController::class, 'create'])->middleware('toapp.admin.can:plans')->name('plans.create');
    Route::post('/plans', [PlanController::class, 'store'])->middleware('toapp.admin.can:plans')->name('plans.store');
    Route::get('/plans/{plan}/edit', [PlanController::class, 'edit'])->middleware('toapp.admin.can:plans')->name('plans.edit');
    Route::put('/plans/{plan}', [PlanController::class, 'update'])->middleware('toapp.admin.can:plans')->name('plans.update');
    Route::post('/plans/{plan}/status', [PlanController::class, 'status'])->middleware('toapp.admin.can:plans')->name('plans.status');

    Route::get('/applications', [InsuranceApplicationController::class, 'index'])->middleware('toapp.admin.can:reports')->name('applications.index');
    Route::get('/applications/{application}', [InsuranceApplicationController::class, 'show'])->middleware('toapp.admin.can:reports')->name('applications.show');
    Route::post('/applications/{application}/confirm', [InsuranceApplicationController::class, 'confirm'])->middleware('toapp.admin.can:investments')->name('applications.confirm');
    Route::post('/applications/{application}/reject', [InsuranceApplicationController::class, 'reject'])->middleware('toapp.admin.can:investments')->name('applications.reject');

    Route::get('/deposits', [DepositController::class, 'index'])->middleware('toapp.admin.can:deposits')->name('deposits.index');
    Route::get('/deposits/export', [DepositController::class, 'export'])->middleware('toapp.admin.can:deposits')->name('deposits.export');
    Route::get('/deposits/{deposit}', [DepositController::class, 'show'])->middleware('toapp.admin.can:deposits')->name('deposits.show');
    Route::post('/deposits/{deposit}/approve', [DepositController::class, 'approve'])->middleware('toapp.admin.can:deposits')->name('deposits.approve');
    Route::post('/deposits/{deposit}/reject', [DepositController::class, 'reject'])->middleware('toapp.admin.can:deposits')->name('deposits.reject');

    Route::get('/withdrawals', [WithdrawalController::class, 'index'])->middleware('toapp.admin.can:withdrawals')->name('withdrawals.index');
    Route::get('/withdrawals/export', [WithdrawalController::class, 'export'])->middleware('toapp.admin.can:withdrawals')->name('withdrawals.export');
    Route::get('/withdrawals/{withdrawal}', [WithdrawalController::class, 'show'])->middleware('toapp.admin.can:withdrawals')->name('withdrawals.show');
    Route::post('/withdrawals/{withdrawal}/approve', [WithdrawalController::class, 'approve'])->middleware('toapp.admin.can:withdrawals')->name('withdrawals.approve');
    Route::post('/withdrawals/{withdrawal}/reject', [WithdrawalController::class, 'reject'])->middleware('toapp.admin.can:withdrawals')->name('withdrawals.reject');

    Route::get('/reports/transactions', [ReportController::class, 'transactions'])->middleware('toapp.admin.can:reports')->name('reports.transactions');
    Route::get('/reports/transactions/export', [ReportController::class, 'transactionsExport'])->middleware('toapp.admin.can:reports')->name('reports.transactions.export');
    Route::get('/reports/investments', [ReportController::class, 'investments'])->middleware('toapp.admin.can:reports,investments')->name('reports.investments');
    Route::get('/reports/investments/{investment}', [ReportController::class, 'showInvestment'])->middleware('toapp.admin.can:reports,investments')->name('reports.investments.show');
    Route::get('/reports/investments/export', [ReportController::class, 'investmentsExport'])->middleware('toapp.admin.can:reports,investments')->name('reports.investments.export');
    Route::post('/reports/investments/{investment}/approve', [ReportController::class, 'approveInvestment'])->middleware('toapp.admin.can:investments')->name('reports.investments.approve');
    Route::post('/reports/investments/{investment}/reject', [ReportController::class, 'rejectInvestment'])->middleware('toapp.admin.can:investments')->name('reports.investments.reject');
    Route::get('/reports/logins', [ReportController::class, 'logins'])->middleware('toapp.admin.can:reports')->name('reports.logins');
    Route::get('/reports/logins/export', [ReportController::class, 'loginsExport'])->middleware('toapp.admin.can:reports')->name('reports.logins.export');
    Route::get('/reports/audits', [ReportController::class, 'audits'])->middleware('toapp.admin.can:reports')->name('reports.audits');
    Route::get('/reports/agents', [ReportController::class, 'agents'])->middleware('toapp.admin.can:agents')->name('reports.agents');
    Route::get('/reports/agents/export', [ReportController::class, 'agentsExport'])->middleware('toapp.admin.can:agents')->name('reports.agents.export');
    Route::get('/reports/agents/{agent}', [ReportController::class, 'showAgent'])->middleware('toapp.admin.can:agents')->name('reports.agents.show');

    Route::get('/referrals', [ReferralController::class, 'index'])->middleware('toapp.admin.can:referrals')->name('referrals.index');
    Route::get('/referrals/rules/create', [ReferralController::class, 'createRule'])->middleware('toapp.admin.can:referrals')->name('referrals.rules.create');
    Route::get('/referrals/export', [ReferralController::class, 'export'])->middleware('toapp.admin.can:referrals')->name('referrals.export');
    Route::post('/referrals/rules', [ReferralController::class, 'storeRule'])->middleware('toapp.admin.can:referrals')->name('referrals.rules.store');
    Route::delete('/referrals/rules/{referral}', [ReferralController::class, 'destroyRule'])->middleware('toapp.admin.can:referrals')->name('referrals.rules.destroy');

    Route::get('/agents', [AgentController::class, 'index'])->middleware('toapp.admin.can:agents')->name('agents.index');
    Route::get('/agents/zip-catalog/states', [AgentController::class, 'zipStates'])->middleware('toapp.admin.can:agents')->name('agents.zip_states');
    Route::get('/agents/zip-catalog/all', [AgentController::class, 'zipAll'])->middleware('toapp.admin.can:agents')->name('agents.zip_all');
    Route::get('/agents/zip-catalog/states/{state}', [AgentController::class, 'zipStateDetail'])->middleware('toapp.admin.can:agents')->name('agents.zip_state');
    Route::get('/agents/create', [AgentController::class, 'create'])->middleware('toapp.admin.can:agents')->name('agents.create');
    Route::post('/agents', [AgentController::class, 'store'])->middleware('toapp.admin.can:agents')->name('agents.store');
    Route::get('/agents/{agent}/edit', [AgentController::class, 'edit'])->middleware('toapp.admin.can:agents')->name('agents.edit');
    Route::put('/agents/{agent}', [AgentController::class, 'update'])->middleware('toapp.admin.can:agents')->name('agents.update');

    Route::get('/support', [SupportController::class, 'index'])->middleware('toapp.admin.can:support')->name('support.index');
    Route::get('/support/{ticket}', [SupportController::class, 'show'])->middleware('toapp.admin.can:support')->name('support.show');
    Route::post('/support/{ticket}/reply', [SupportController::class, 'reply'])->middleware('toapp.admin.can:support')->name('support.reply');
    Route::post('/support/{ticket}/close', [SupportController::class, 'close'])->middleware('toapp.admin.can:support')->name('support.close');

    Route::get('/methods', [MethodController::class, 'index'])->middleware('toapp.admin.can:methods')->name('methods.index');
    Route::get('/methods/deposit/{gateway}/edit', [MethodController::class, 'editDepositGateway'])->middleware('toapp.admin.can:methods')->name('methods.deposit.edit');
    Route::get('/methods/withdraw/{method}/edit', [MethodController::class, 'editWithdrawMethod'])->middleware('toapp.admin.can:methods')->name('methods.withdraw.edit');
    Route::put('/methods/deposit/{gateway}', [MethodController::class, 'updateDepositGateway'])->middleware('toapp.admin.can:methods')->name('methods.deposit.update');
    Route::put('/methods/withdraw/{method}', [MethodController::class, 'updateWithdrawMethod'])->middleware('toapp.admin.can:methods')->name('methods.withdraw.update');
    Route::post('/methods/deposit/{gateway}/status', [MethodController::class, 'toggleDepositGateway'])->middleware('toapp.admin.can:methods')->name('methods.deposit.status');
    Route::post('/methods/withdraw/{method}/status', [MethodController::class, 'toggleWithdrawMethod'])->middleware('toapp.admin.can:methods')->name('methods.withdraw.status');

    Route::get('/readiness', [ReadinessController::class, 'index'])->middleware('toapp.admin.can:readiness')->name('readiness.index');

    Route::get('/settings', [SettingController::class, 'edit'])->middleware('toapp.admin.can:settings')->name('settings.edit');
    Route::put('/settings', [SettingController::class, 'update'])->middleware('toapp.admin.can:settings')->name('settings.update');
    Route::get('/settings/email', [SettingController::class, 'email'])->middleware('toapp.admin.can:settings')->name('settings.email');
    Route::put('/settings/email', [SettingController::class, 'updateEmail'])->middleware('toapp.admin.can:settings')->name('settings.email.update');
    Route::post('/settings/email/test', [SettingController::class, 'testEmail'])->middleware('toapp.admin.can:settings')->name('settings.email.test');
});
