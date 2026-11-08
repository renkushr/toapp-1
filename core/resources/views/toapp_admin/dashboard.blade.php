@extends('toapp_admin.layouts.app')

@section('content')
<div class="row row-deck row-cards mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-users"></i></span>
                <div class="subheader">@lang('Total Users')</div>
                <div class="h1 mb-1">{{ number_format($stats['users']) }}</div>
                <div class="text-secondary">{{ number_format($stats['active_users']) }} @lang('active')</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">@lang('Pending Approval')</div>
                <div class="h1 mb-1 text-warning">{{ number_format($stats['pending_users']) }}</div>
                <div class="text-secondary">@lang('New members to review')</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">@lang('Plans')</div>
                <div class="h1 mb-1">{{ number_format($stats['plans']) }}</div>
                <div class="text-secondary">{{ number_format($stats['running_investments']) }} @lang('running')</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">@lang('Referral Paid')</div>
                <div class="h1 mb-1 text-success">{{ number_format($stats['referral_paid'], 2) }}</div>
                <div class="text-secondary">@lang('Referral fee ledger')</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">@lang('No Agent Coverage')</div>
                <div class="h1 mb-1 text-warning">{{ number_format($stats['unassigned_users']) }}</div>
                <div class="text-secondary">
                    <a href="{{ route('toapp.admin.users.index', ['agent' => 'unassigned']) }}">@lang('View customers')</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row row-deck row-cards mb-4">
    <div class="col-sm-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="subheader">@lang('Pending Packages')</div>
                <div class="h2">{{ number_format($stats['pending_investments']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="subheader">@lang('Pending Deposits')</div>
                <div class="h2">{{ number_format($stats['pending_deposits']) }}</div>
                <div class="text-secondary">{{ number_format($stats['total_deposited'], 2) }} @lang('total')</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="subheader">@lang('Pending Withdrawals')</div>
                <div class="h2 text-danger">{{ number_format($stats['pending_withdrawals']) }}</div>
                <div class="text-secondary">{{ number_format($stats['total_withdrawn'], 2) }} @lang('total')</div>
            </div>
        </div>
    </div>
</div>

<div class="row row-cards">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Latest Users')</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>@lang('User')</th>
                            <th>@lang('Email')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Joined')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers as $user)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ trim($user->firstname . ' ' . $user->lastname) ?: $user->username }}</div>
                                    <div class="text-secondary">{{ '@' . $user->username }}</div>
                                </td>
                                <td class="text-secondary">{{ $user->email }}</td>
                                <td>
                                    <span class="badge {{ $user->status ? 'bg-success-lt' : 'bg-danger-lt' }}">{{ $user->status ? __('Active') : __('Banned') }}</span>
                                    @php
                                        $approvalClass = $user->approval_status === 'approved' ? 'bg-success-lt' : ($user->approval_status === 'rejected' ? 'bg-danger-lt' : 'bg-warning-lt');
                                        $approvalLabel = ucfirst($user->approval_status ?? 'approved');
                                    @endphp
                                    <span class="badge {{ $approvalClass }}">{{ __($approvalLabel) }}</span>
                                </td>
                                <td class="text-secondary">{{ optional($user->created_at)->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-secondary">@lang('No users yet.')</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Recent Transactions')</h3>
            </div>
            <div class="list-group list-group-flush">
                @forelse($recentTransactions as $trx)
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="fw-bold">{{ $trx->trx ?? __('Transaction') }}</div>
                                <div class="text-secondary">{{ optional($trx->user)->username ?? __('System') }} · {{ optional($trx->created_at)->format('M d, H:i') }}</div>
                            </div>
                            <div class="col-auto">
                                <span class="{{ ($trx->trx_type ?? '+') === '+' ? 'text-success' : 'text-danger' }} fw-bold">
                                    {{ $trx->trx_type ?? '' }}{{ number_format((float) $trx->amount, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="list-group-item text-secondary">@lang('No transactions yet.')</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row row-cards mt-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">@lang('Latest Deposits')</h3></div>
            <div class="list-group list-group-flush">
                @forelse($recentDeposits as $deposit)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="fw-bold">{{ optional($deposit->user)->username ?? __('Unknown user') }}</div>
                                <div class="text-secondary">{{ $deposit->trx }}</div>
                            </div>
                            <div class="fw-bold">{{ number_format((float) $deposit->amount, 2) }}</div>
                        </div>
                    </div>
                @empty
                    <div class="list-group-item text-secondary">@lang('No deposits yet.')</div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">@lang('Latest Withdrawals')</h3></div>
            <div class="list-group list-group-flush">
                @forelse($recentWithdrawals as $withdrawal)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="fw-bold">{{ optional($withdrawal->user)->username ?? __('Unknown user') }}</div>
                                <div class="text-secondary">{{ optional($withdrawal->method)->name ?? __('Method') }}</div>
                            </div>
                            <div class="fw-bold">{{ number_format((float) $withdrawal->amount, 2) }}</div>
                        </div>
                    </div>
                @empty
                    <div class="list-group-item text-secondary">@lang('No withdrawals yet.')</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
