@extends('toapp_admin.layouts.app')

@section('content')
@php
    $admin = auth('admin')->user();
    $displayName = trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? '')) ?: ($user->username ?: 'Member');
    $approval = $user->approval_status ?? 'approved';
    $approvalClass = match ($approval) {
        'approved' => 'success',
        'rejected' => 'danger',
        default => 'warning',
    };
@endphp

<div class="portal-page-actions mb-3">
    <a href="{{ route('toapp.admin.users.index') }}" class="btn btn-ghost-secondary btn-sm">
        <i class="ti ti-arrow-left me-1"></i> Back to users
    </a>
    <span class="badge {{ $user->status ? 'bg-success-lt' : 'bg-danger-lt' }}">{{ $user->status ? 'Active' : 'Banned' }}</span>
    <span class="badge bg-{{ $approvalClass }}-lt">{{ ucfirst($approval) }}</span>
    @if(!$user->agent_id && filled($user->zipcode))
        <span class="badge bg-warning-lt">No agent for ZIP code {{ $user->zipcode }}</span>
    @endif
</div>

<section class="ta-grid ta-grid-stats">
    <div class="ta-stat">
        <span>Balance</span>
        <strong>{{ number_format((float) $user->balance, 2) }}</strong>
        <small>Current wallet</small>
    </div>
    <div class="ta-stat">
        <span>Total deposit</span>
        <strong>{{ number_format((float) $totalDeposit, 2) }}</strong>
        <small>Successful only</small>
    </div>
    <div class="ta-stat">
        <span>Total withdraw</span>
        <strong>{{ number_format((float) $totalWithdrawals, 2) }}</strong>
        <small>Approved only</small>
    </div>
    <div class="ta-stat">
        <span>Referral fee earned</span>
        <strong>{{ number_format((float) $totalReferralCommission, 2) }}</strong>
        <small>{{ $directReferralCount }} direct referrals</small>
    </div>
</section>

<section class="ta-grid ta-grid-main">
    <article class="ta-panel">
        <div class="ta-panel-head">
            <div>
                <span class="ta-kicker">Profile</span>
                <h2>{{ $displayName }}</h2>
            </div>
        </div>
        <div class="ta-detail-list">
            <div><span>Username</span><strong>{{ $user->username ?: '—' }}</strong></div>
            <div><span>Email</span><strong>{{ $user->email ?: '—' }}</strong></div>
            <div><span>Mobile</span><strong>{{ trim(($user->dial_code ?? '') . ($user->mobile ?? '')) ?: '—' }}</strong></div>
            <div><span>Country</span><strong>{{ $user->country_name ?: '—' }}</strong></div>
            <div><span>US ZIP code</span><strong>{{ $user->zipcode ?: '—' }}</strong></div>
            @php
                $referrerName = $referrer ? trim(($referrer->firstname ?? '') . ' ' . ($referrer->lastname ?? '')) : '';
                if ($referrer && $referrerName === '') {
                    $referrerName = $referrer->username ?? '';
                }
            @endphp
            <div><span>Referrer</span><strong>{{ $referrer ? censorReferrerName($referrer->firstname, $referrer->lastname, $referrerName !== '' ? $referrerName : null) : '—' }}</strong></div>
            <div><span>Referrer email</span><strong>{{ $referrer ? censorReferrerEmail($referrer->email) : '—' }}</strong></div>
            <div><span>Referral code</span><strong>{{ $user->refno ?: '—' }}</strong></div>
            <div>
                <span>Assigned agent</span>
                <strong>
                    @if($user->agent)
                        {{ $user->agent->name }} ({{ $user->agent->license_no }})
                    @else
                        —
                    @endif
                </strong>
            </div>
            <div><span>Ban reason</span><strong>{{ $user->ban_reason ?: '—' }}</strong></div>
            @if($user->rejection_reason)
                <div><span>Reject reason</span><strong>{{ $user->rejection_reason }}</strong></div>
            @endif
        </div>
    </article>

    <article class="ta-panel">
        <div class="ta-panel-head">
            <div>
                <span class="ta-kicker">Territory</span>
                <h2>Assign agent</h2>
            </div>
        </div>
        <form method="POST" action="{{ route('toapp.admin.users.agent', $user) }}" class="ta-form-stack">
            @csrf
            <label class="ta-field">
                <span>Agent</span>
                <select name="agent_id" required>
                    <option value="">Select agent</option>
                    @foreach($agents as $agentOption)
                        <option value="{{ $agentOption->id }}" @selected((int) $user->agent_id === (int) $agentOption->id)>
                            {{ $agentOption->name }} — {{ $agentOption->license_no }}
                        </option>
                    @endforeach
                </select>
            </label>
            <label class="ta-field">
                <span>Member US ZIP code</span>
                <input name="zipcode" value="{{ old('zipcode', $user->zipcode) }}" placeholder="90210" pattern="\d{5}(-\d{4})?" required>
                <small class="text-secondary">ZIP code must fall within the selected agent's coverage.</small>
            </label>
            <button class="ta-primary-btn" type="submit">Save agent assignment</button>
        </form>
    </article>
</section>

<section class="ta-grid ta-grid-main">
    <article class="ta-panel">
        <div class="ta-panel-head">
            <div>
                <span class="ta-kicker">Controls</span>
                <h2>Account actions</h2>
            </div>
        </div>
        <div class="ta-form-stack">
            @if($approval !== 'approved')
                <form method="POST" action="{{ route('toapp.admin.users.approve', $user) }}">
                    @csrf
                    <button class="ta-primary-btn" type="submit">Approve member</button>
                </form>
            @endif

            @if($approval !== 'rejected')
                <form method="POST" action="{{ route('toapp.admin.users.reject', $user) }}">
                    @csrf
                    <label class="ta-field">
                        <span>Reject reason</span>
                        <textarea name="reason" rows="3" required maxlength="1000" placeholder="Explain why this member cannot be approved yet"></textarea>
                    </label>
                    <button class="ta-danger-btn" type="submit">Reject member</button>
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
                <label class="ta-field">
                    <span>KYC status</span>
                    <select name="kv">
                        <option value="0" @selected((int) $user->kv === 0)>Unverified</option>
                        <option value="1" @selected((int) $user->kv === 1)>Verified</option>
                        <option value="2" @selected((int) $user->kv === 2)>Pending</option>
                    </select>
                </label>
                <button class="ta-secondary-btn" type="submit">Save verification</button>
            </form>

            <form method="POST" action="{{ route('toapp.admin.users.status', $user) }}">
                @csrf
                @if($user->status)
                    <label class="ta-field">
                        <span>Ban reason</span>
                        <input name="reason" required maxlength="255" placeholder="Reason shown in admin notes">
                    </label>
                    <button class="ta-danger-btn" type="submit">Ban user</button>
                @else
                    <button class="ta-primary-btn" type="submit">Restore user</button>
                @endif
            </form>
        </div>
    </article>

    @if($admin?->canAccess('balances'))
        <article class="ta-panel">
            <div class="ta-panel-head">
                <div>
                    <span class="ta-kicker">Wallet</span>
                    <h2>Balance adjustment</h2>
                </div>
            </div>
            <form method="POST" action="{{ route('toapp.admin.users.balance', $user) }}" class="ta-form-stack">
                @csrf
                <label class="ta-field">
                    <span>Action</span>
                    <select name="act">
                        <option value="add">Add</option>
                        <option value="sub">Subtract</option>
                    </select>
                </label>
                <label class="ta-field">
                    <span>Amount</span>
                    <input name="amount" type="number" step="0.01" min="0.01" required>
                </label>
                <label class="ta-field">
                    <span>Remark</span>
                    <input name="remark" required maxlength="255" placeholder="Why this adjustment is needed">
                </label>
                <button class="ta-primary-btn" type="submit">Update balance</button>
            </form>
        </article>
    @endif
</section>

<article class="ta-panel">
    <div class="ta-panel-head">
        <div>
            <span class="ta-kicker">Ledger</span>
            <h2>Recent transactions</h2>
        </div>
    </div>
    <div class="ta-list">
        @forelse($transactions as $trx)
            <div class="ta-list-row">
                <div>
                    <strong>{{ $trx->trx }}</strong>
                    <small>{{ $trx->details ?: $trx->remark }} · {{ optional($trx->created_at)->format('M d, H:i') }}</small>
                </div>
                <span class="{{ $trx->trx_type === '+' ? 'ta-money-plus' : 'ta-money-minus' }}">
                    {{ $trx->trx_type }}{{ number_format((float) $trx->amount, 2) }}
                </span>
            </div>
        @empty
            <div class="ta-empty">No transactions yet.</div>
        @endforelse
    </div>
</article>
@endsection
