@extends('toapp_admin.layouts.app')

@section('content')
@php
    $admin = auth('admin')->user();
@endphp

<div class="row row-deck row-cards mb-4">
    @foreach([
        ['Balance', number_format((float) $user->balance, 2), 'Current wallet'],
        ['Total deposit', number_format((float) $totalDeposit, 2), 'Successful only'],
        ['Total withdraw', number_format((float) $totalWithdrawals, 2), 'Approved only'],
        ['Referral earned', number_format((float) $totalReferralCommission, 2), $directReferralCount . ' direct referrals'],
    ] as [$label, $value, $hint])
        <div class="col-sm-6 col-xl-3">
            <div class="card portal-stat-card">
                <div class="card-body">
                    <div class="subheader">{{ $label }}</div>
                    <div class="h2 mb-1">{{ $value }}</div>
                    <div class="text-secondary">{{ $hint }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <div class="card-subtitle text-secondary mb-1">Profile</div>
            <h3 class="card-title mb-0">{{ trim($user->firstname . ' ' . $user->lastname) ?: $user->username }}</h3>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('toapp.admin.users.index') }}">Back to users</a>
    </div>
    <div class="list-group list-group-flush portal-detail-list">
        @foreach([
            ['Username', $user->username],
            ['Email', $user->email],
            ['Mobile', $user->dial_code . $user->mobile],
            ['Country', $user->country_name ?: '—'],
            ['Status', $user->status ? 'Active' : 'Banned'],
            ['Approval', ucfirst($user->approval_status ?? 'approved')],
            ['Referrer', $referrer?->username ?? '—'],
            ['Referral code', $user->refno ?: '—'],
            ['US ZIP', $user->zipcode ?: '—'],
            ['Assigned agent', optional($user->agent)->name ? $user->agent->name . ' (' . $user->agent->license_no . ')' : '—'],
            ['Ban reason', $user->ban_reason ?: '—'],
        ] as [$label, $value])
            <div class="list-group-item portal-detail-row">
                <span class="text-secondary">{{ $label }}</span>
                <strong class="text-end">{{ $value }}</strong>
            </div>
        @endforeach
        @if($user->rejection_reason)
            <div class="list-group-item portal-detail-row">
                <span class="text-secondary">Reject reason</span>
                <strong class="text-end">{{ $user->rejection_reason }}</strong>
            </div>
        @endif
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <div class="card-subtitle text-secondary mb-1">Territory</div>
        <h3 class="card-title mb-0">Assign agent</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('toapp.admin.users.agent', $user) }}" class="portal-form-stack">
            @csrf
            <div class="mb-3">
                <label class="form-label">Agent</label>
                <select class="form-select" name="agent_id" required>
                    <option value="">Select agent</option>
                    @foreach($agents as $agentOption)
                        <option value="{{ $agentOption->id }}" @selected($user->agent_id == $agentOption->id)>
                            {{ $agentOption->name }} — {{ $agentOption->license_no }} ({{ $agentOption->zipCodeList() }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Member US ZIP</label>
                <input class="form-control" name="zipcode" value="{{ old('zipcode', $user->zipcode) }}" placeholder="90210" pattern="\d{5}(-\d{4})?" required>
                <div class="form-hint">ZIP must fall within the selected agent's coverage area.</div>
            </div>
            @error('agent_id')<div class="alert alert-danger">{{ $message }}</div>@enderror
            @error('zipcode')<div class="alert alert-danger">{{ $message }}</div>@enderror
            <button class="btn btn-primary" type="submit">Save agent assignment</button>
        </form>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <div class="card-subtitle text-secondary mb-1">Controls</div>
        <h3 class="card-title mb-0">Account actions</h3>
    </div>
    <div class="card-body portal-form-stack">
        @if(($user->approval_status ?? 'approved') !== 'approved')
            <form method="POST" action="{{ route('toapp.admin.users.approve', $user) }}">
                @csrf
                <button class="btn btn-primary" type="submit">Approve member</button>
            </form>
        @endif

        @if(($user->approval_status ?? 'approved') !== 'rejected')
            <form method="POST" action="{{ route('toapp.admin.users.reject', $user) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Reject reason</label>
                    <textarea class="form-control" name="reason" rows="3" required maxlength="1000" placeholder="Explain why this member cannot be approved yet"></textarea>
                </div>
                <button class="btn btn-danger" type="submit">Reject member</button>
            </form>
        @endif

        <form method="POST" action="{{ route('toapp.admin.users.verification', $user) }}">
            @csrf
            <div class="portal-check-stack mb-3">
                <label class="form-check">
                    <input class="form-check-input" type="checkbox" name="ev" value="1" @checked($user->ev)>
                    <span class="form-check-label">Email verified</span>
                </label>
                <label class="form-check">
                    <input class="form-check-input" type="checkbox" name="sv" value="1" @checked($user->sv)>
                    <span class="form-check-label">Mobile verified</span>
                </label>
            </div>
            <div class="mb-3">
                <label class="form-label">KYC status</label>
                <select class="form-select" name="kv">
                    <option value="0" @selected($user->kv == 0)>Unverified</option>
                    <option value="1" @selected($user->kv == 1)>Verified</option>
                    <option value="2" @selected($user->kv == 2)>Pending</option>
                </select>
            </div>
            <button class="btn btn-outline-primary" type="submit">Save verification</button>
        </form>

        <form method="POST" action="{{ route('toapp.admin.users.status', $user) }}">
            @csrf
            @if($user->status)
                <div class="mb-3">
                    <label class="form-label">Ban reason</label>
                    <input class="form-control" name="reason" required maxlength="255" placeholder="Reason shown in admin notes">
                </div>
                <button class="btn btn-danger" type="submit">Ban user</button>
            @else
                <button class="btn btn-primary" type="submit">Restore user</button>
            @endif
        </form>

        @if($admin?->canAccess('balances'))
            <form method="POST" action="{{ route('toapp.admin.users.balance', $user) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Action</label>
                    <select class="form-select" name="act">
                        <option value="add">Add</option>
                        <option value="sub">Subtract</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Amount</label>
                    <input class="form-control" name="amount" type="number" step="0.01" min="0.01" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Remark</label>
                    <input class="form-control" name="remark" required maxlength="255" placeholder="Why this adjustment is needed">
                </div>
                <button class="btn btn-outline-primary" type="submit">Update balance</button>
            </form>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-subtitle text-secondary mb-1">Ledger</div>
        <h3 class="card-title mb-0">Recent transactions</h3>
    </div>
    <div class="list-group list-group-flush">
        @forelse($transactions as $trx)
            <div class="list-group-item d-flex align-items-center justify-content-between gap-3">
                <div>
                    <div class="fw-bold">{{ $trx->trx }}</div>
                    <div class="text-secondary small">{{ $trx->details ?: $trx->remark }} · {{ optional($trx->created_at)->format('M d, H:i') }}</div>
                </div>
                <span class="fw-bold {{ $trx->trx_type === '+' ? 'text-success' : 'text-danger' }}">
                    {{ $trx->trx_type }}{{ number_format((float) $trx->amount, 2) }}
                </span>
            </div>
        @empty
            <div class="list-group-item text-secondary">No transactions yet.</div>
        @endforelse
    </div>
</div>
@endsection
