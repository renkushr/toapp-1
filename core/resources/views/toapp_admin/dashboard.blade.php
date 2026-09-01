@extends('toapp_admin.layouts.app')

@section('content')
<div class="row row-deck row-cards mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-users"></i></span>
                <div class="subheader">Total Users</div>
                <div class="h1 mb-1">{{ number_format($stats['users']) }}</div>
                <div class="text-secondary">{{ number_format($stats['active_users']) }} active</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Pending Approval</div>
                <div class="h1 mb-1 text-warning">{{ number_format($stats['pending_users']) }}</div>
                <div class="text-secondary">New members to review</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Plans</div>
                <div class="h1 mb-1">{{ number_format($stats['plans']) }}</div>
                <div class="text-secondary">{{ number_format($stats['running_investments']) }} running</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Referral Paid</div>
                <div class="h1 mb-1 text-success">{{ number_format($stats['referral_paid'], 2) }}</div>
                <div class="text-secondary">Referral fee ledger</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">No Agent Coverage</div>
                <div class="h1 mb-1 text-warning">{{ number_format($stats['unassigned_users']) }}</div>
                <div class="text-secondary">
                    <a href="{{ route('toapp.admin.users.index', ['agent' => 'unassigned']) }}">View customers</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row row-deck row-cards mb-4">
    <div class="col-sm-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Pending Packages</div>
                <div class="h2">{{ number_format($stats['pending_investments']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Pending Deposits</div>
                <div class="h2">{{ number_format($stats['pending_deposits']) }}</div>
                <div class="text-secondary">{{ number_format($stats['total_deposited'], 2) }} total</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Pending Withdrawals</div>
                <div class="h2 text-danger">{{ number_format($stats['pending_withdrawals']) }}</div>
                <div class="text-secondary">{{ number_format($stats['total_withdrawn'], 2) }} total</div>
            </div>
        </div>
    </div>
</div>

<div class="row row-cards">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Latest Users</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Joined</th>
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
                                    <span class="badge {{ $user->status ? 'bg-success-lt' : 'bg-danger-lt' }}">{{ $user->status ? 'Active' : 'Banned' }}</span>
                                    @php
                                        $approvalClass = $user->approval_status === 'approved' ? 'bg-success-lt' : ($user->approval_status === 'rejected' ? 'bg-danger-lt' : 'bg-warning-lt');
                                    @endphp
                                    <span class="badge {{ $approvalClass }}">{{ ucfirst($user->approval_status ?? 'approved') }}</span>
                                </td>
                                <td class="text-secondary">{{ optional($user->created_at)->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-secondary">No users yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Transactions</h3>
            </div>
            <div class="list-group list-group-flush">
                @forelse($recentTransactions as $trx)
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="fw-bold">{{ $trx->trx ?? 'Transaction' }}</div>
                                <div class="text-secondary">{{ optional($trx->user)->username ?? 'System' }} · {{ optional($trx->created_at)->format('M d, H:i') }}</div>
                            </div>
                            <div class="col-auto">
                                <span class="{{ ($trx->trx_type ?? '+') === '+' ? 'text-success' : 'text-danger' }} fw-bold">
                                    {{ $trx->trx_type ?? '' }}{{ number_format((float) $trx->amount, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="list-group-item text-secondary">No transactions yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row row-cards mt-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Latest Deposits</h3></div>
            <div class="list-group list-group-flush">
                @forelse($recentDeposits as $deposit)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="fw-bold">{{ optional($deposit->user)->username ?? 'Unknown user' }}</div>
                                <div class="text-secondary">{{ $deposit->trx }}</div>
                            </div>
                            <div class="fw-bold">{{ number_format((float) $deposit->amount, 2) }}</div>
                        </div>
                    </div>
                @empty
                    <div class="list-group-item text-secondary">No deposits yet.</div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Latest Withdrawals</h3></div>
            <div class="list-group list-group-flush">
                @forelse($recentWithdrawals as $withdrawal)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="fw-bold">{{ optional($withdrawal->user)->username ?? 'Unknown user' }}</div>
                                <div class="text-secondary">{{ optional($withdrawal->method)->name ?? 'Method' }}</div>
                            </div>
                            <div class="fw-bold">{{ number_format((float) $withdrawal->amount, 2) }}</div>
                        </div>
                    </div>
                @empty
                    <div class="list-group-item text-secondary">No withdrawals yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
