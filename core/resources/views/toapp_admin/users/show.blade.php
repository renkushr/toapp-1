@extends('toapp_admin.layouts.app')

@section('content')
@php
    $admin = auth('admin')->user();
    $displayName = trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? '')) ?: ($user->username ?: __('Member'));
    $approval = $user->approval_status ?? 'approved';
    $approvalClass = match ($approval) {
        'approved' => 'success',
        'rejected' => 'danger',
        default => 'warning',
    };
@endphp

<div class="portal-page-actions mb-3">
    <a href="{{ route('toapp.admin.users.index') }}" class="btn btn-ghost-secondary btn-sm">
        <i class="ti ti-arrow-left me-1"></i> @lang('Back to users')
    </a>
    <span class="badge {{ $user->status ? 'bg-success-lt' : 'bg-danger-lt' }}">{{ $user->status ? __('Active') : __('Banned') }}</span>
    <span class="badge bg-{{ $approvalClass }}-lt">{{ __(ucfirst($approval)) }}</span>
    @if(!$user->agent_id && filled($user->zipcode))
        <span class="badge bg-warning-lt">@lang('No agent for ZIP code') {{ $user->zipcode }}</span>
    @endif
</div>

<section class="ta-grid ta-grid-stats">
    <div class="ta-stat">
        <span>@lang('Balance')</span>
        <strong>{{ number_format((float) $user->balance, 2) }}</strong>
        <small>@lang('Current wallet')</small>
    </div>
    <div class="ta-stat">
        <span>@lang('Total deposit')</span>
        <strong>{{ number_format((float) $totalDeposit, 2) }}</strong>
        <small>@lang('Successful only')</small>
    </div>
    <div class="ta-stat">
        <span>@lang('Total withdraw')</span>
        <strong>{{ number_format((float) $totalWithdrawals, 2) }}</strong>
        <small>@lang('Approved only')</small>
    </div>
    <div class="ta-stat">
        <span>@lang('Referral fee earned')</span>
        <strong>{{ number_format((float) $totalReferralCommission, 2) }}</strong>
        <small>{{ $directReferralCount }} @lang('direct referrals')</small>
    </div>
</section>

<section class="ta-grid ta-grid-main">
    <article class="ta-panel">
        <div class="ta-panel-head">
            <div>
                <span class="ta-kicker">@lang('Profile')</span>
                <h2>{{ $displayName }}</h2>
            </div>
        </div>
        <div class="ta-detail-list">
            <div><span>@lang('Username')</span><strong>{{ $user->username ?: '—' }}</strong></div>
            <div><span>@lang('Email')</span><strong>{{ $user->email ?: '—' }}</strong></div>
            <div><span>@lang('Mobile')</span><strong>{{ trim(($user->dial_code ?? '') . ($user->mobile ?? '')) ?: '—' }}</strong></div>
            <div><span>@lang('Country')</span><strong>{{ $user->country_name ?: '—' }}</strong></div>
            <div><span>@lang('US ZIP code')</span><strong>{{ $user->zipcode ?: '—' }}</strong></div>
            @php
                $referrerName = $referrer ? trim(($referrer->firstname ?? '') . ' ' . ($referrer->lastname ?? '')) : '';
                if ($referrer && $referrerName === '') {
                    $referrerName = $referrer->username ?? '';
                }
            @endphp
            <div><span>@lang('Referrer')</span><strong>{{ $referrer ? censorReferrerName($referrer->firstname, $referrer->lastname, $referrerName !== '' ? $referrerName : null) : '—' }}</strong></div>
            <div><span>@lang('Referrer email')</span><strong>{{ $referrer ? censorReferrerEmail($referrer->email) : '—' }}</strong></div>
            <div><span>@lang('Referral code')</span><strong>{{ $user->refno ?: '—' }}</strong></div>
            <div>
                <span>@lang('Assigned agent')</span>
                <strong>
                    @if($user->agent)
                        {{ $user->agent->name }} ({{ $user->agent->license_no }})
                    @else
                        —
                    @endif
                </strong>
            </div>
            <div><span>@lang('Ban reason')</span><strong>{{ $user->ban_reason ?: '—' }}</strong></div>
            @if($user->rejection_reason)
                <div><span>@lang('Reject reason')</span><strong>{{ $user->rejection_reason }}</strong></div>
            @endif
        </div>
    </article>

    <article class="ta-panel">
        <div class="ta-panel-head">
            <div>
                <span class="ta-kicker">@lang('Territory')</span>
                <h2>@lang('Assign agent')</h2>
            </div>
        </div>
        <form method="POST" action="{{ route('toapp.admin.users.agent', $user) }}" class="ta-form-stack">
            @csrf
            <label class="ta-field">
                <span>@lang('Agent')</span>
                <select name="agent_id" required>
                    <option value="">@lang('Select agent')</option>
                    @foreach($agents as $agentOption)
                        <option value="{{ $agentOption->id }}" @selected((int) $user->agent_id === (int) $agentOption->id)>
                            {{ $agentOption->name }} — {{ $agentOption->license_no }}
                        </option>
                    @endforeach
                </select>
            </label>
            <label class="ta-field">
                <span>@lang('Member US ZIP code')</span>
                <input name="zipcode" value="{{ old('zipcode', $user->zipcode) }}" placeholder="90210" pattern="\d{5}(-\d{4})?" required>
                <small class="text-secondary">@lang("ZIP code must fall within the selected agent's coverage.")</small>
            </label>
            <button class="ta-primary-btn" type="submit">@lang('Save agent assignment')</button>
        </form>
    </article>
</section>

<section class="ta-grid ta-grid-main">
    <article class="ta-panel">
        <div class="ta-panel-head">
            <div>
                <span class="ta-kicker">@lang('Controls')</span>
                <h2>@lang('Account actions')</h2>
            </div>
        </div>
        <div class="ta-form-stack">
            @if($approval !== 'approved')
                <form method="POST" action="{{ route('toapp.admin.users.approve', $user) }}">
                    @csrf
                    <button class="ta-primary-btn" type="submit">@lang('Approve member')</button>
                </form>
            @endif

            @if($approval !== 'rejected')
                <form method="POST" action="{{ route('toapp.admin.users.reject', $user) }}">
                    @csrf
                    <label class="ta-field">
                        <span>@lang('Reject reason')</span>
                        <textarea name="reason" rows="3" required maxlength="1000" placeholder="@lang('Explain why this member cannot be approved yet')"></textarea>
                    </label>
                    <button class="ta-danger-btn" type="submit">@lang('Reject member')</button>
                </form>
            @endif

            <form method="POST" action="{{ route('toapp.admin.users.verification', $user) }}">
                @csrf
                <div class="portal-check-stack mb-3">
                    <label class="form-check">
                        <input class="form-check-input" type="checkbox" name="ev" value="1" @checked($user->ev)>
                        <span class="form-check-label">@lang('Email verified')</span>
                    </label>
                    <label class="form-check">
                        <input class="form-check-input" type="checkbox" name="sv" value="1" @checked($user->sv)>
                        <span class="form-check-label">@lang('Mobile verified')</span>
                    </label>
                </div>
                <label class="ta-field">
                    <span>@lang('KYC status')</span>
                    <select name="kv">
                        <option value="0" @selected((int) $user->kv === 0)>@lang('Unverified')</option>
                        <option value="1" @selected((int) $user->kv === 1)>@lang('Verified')</option>
                        <option value="2" @selected((int) $user->kv === 2)>@lang('Pending')</option>
                    </select>
                </label>
                <button class="ta-secondary-btn" type="submit">@lang('Save verification')</button>
            </form>

            <form method="POST" action="{{ route('toapp.admin.users.status', $user) }}">
                @csrf
                @if($user->status)
                    <label class="ta-field">
                        <span>@lang('Ban reason')</span>
                        <input name="reason" required maxlength="255" placeholder="@lang('Reason shown in admin notes')">
                    </label>
                    <button class="ta-danger-btn" type="submit">@lang('Ban user')</button>
                @else
                    <button class="ta-primary-btn" type="submit">@lang('Restore user')</button>
                @endif
            </form>
        </div>
    </article>

    @if($admin?->canAccess('balances'))
        <article class="ta-panel">
            <div class="ta-panel-head">
                <div>
                    <span class="ta-kicker">@lang('Wallet')</span>
                    <h2>@lang('Balance adjustment')</h2>
                </div>
            </div>
            <form method="POST" action="{{ route('toapp.admin.users.balance', $user) }}" class="ta-form-stack">
                @csrf
                <label class="ta-field">
                    <span>@lang('Action')</span>
                    <select name="act">
                        <option value="add">@lang('Add')</option>
                        <option value="sub">@lang('Subtract')</option>
                    </select>
                </label>
                <label class="ta-field">
                    <span>@lang('Amount')</span>
                    <input name="amount" type="number" step="0.01" min="0.01" required>
                </label>
                <label class="ta-field">
                    <span>@lang('Remark')</span>
                    <input name="remark" required maxlength="255" placeholder="@lang('Why this adjustment is needed')">
                </label>
                <button class="ta-primary-btn" type="submit">@lang('Update balance')</button>
            </form>
        </article>
    @endif
</section>

<article class="ta-panel mb-4">
    <div class="ta-panel-head d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <span class="ta-kicker">@lang('Insurance packages')</span>
            <h2>@lang('Applications')</h2>
        </div>
        @if($admin?->canAccess('investments'))
            <form method="POST" action="{{ route('toapp.admin.applications.store', $user) }}">
                @csrf
                <button class="btn btn-primary btn-sm" type="submit"><i class="ti ti-plus me-1"></i> @lang('Add package')</button>
            </form>
        @endif
    </div>
    <div class="ta-list">
        @forelse($applications as $application)
            <div class="ta-list-row">
                <div>
                    <strong>{{ $application->reference }} · {{ $application->plan?->name ?? '—' }}</strong>
                    <small>{{ __($application->statusLabel()) }} · ${{ number_format((float) ($application->quoted_annual ?? 0), 2) }}/yr · {{ $application->agent?->name ?? __('No agent') }}</small>
                </div>
                @if($admin?->canAccess('reports'))
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('toapp.admin.applications.show', $application) }}">@lang('Edit')</a>
                @endif
            </div>
        @empty
            <div class="ta-empty">@lang('No applications yet.')</div>
        @endforelse
    </div>
</article>

<article class="ta-panel">
    <div class="ta-panel-head">
        <div>
            <span class="ta-kicker">@lang('Ledger')</span>
            <h2>@lang('Recent transactions')</h2>
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
            <div class="ta-empty">@lang('No transactions yet.')</div>
        @endforelse
    </div>
</article>
@endsection
