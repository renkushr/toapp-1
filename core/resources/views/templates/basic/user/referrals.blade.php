@extends($activeTemplate . 'layouts.master')

@section('content')
@php
    $levels = $referrals->pluck('level')->unique()->sort()->values();
    $initialLevel = $levels->first() ?? 1;
@endphp
<div class="app-page app-network-page">
    <div class="app-container">
        <div class="app-topbar">
            <a class="app-icon-btn" href="{{ route('user.home') }}" aria-label="@lang('Back to dashboard')">
                <i class="las la-arrow-left"></i>
            </a>
            <a class="app-brand" href="{{ route('user.home') }}">
                <img src="{{ siteLogo() }}" alt="To-App">
                <span>@lang('My Network')</span>
            </a>
            <a class="app-icon-btn" href="{{ route('user.setting') }}" aria-label="@lang('Settings')">
                <i class="las la-cog"></i>
            </a>
        </div>

        <section class="app-hero app-network-hero">
            <span class="app-eyebrow"><i class="las la-sitemap"></i> @lang('Referral workspace')</span>
            <h1>@lang('Grow your member network with a clear link and clear rewards.')</h1>
            <p>@lang('Share your code, monitor each level, and review every referral fee credited from package purchases.')</p>

            <div class="app-copy-box app-network-copy">
                <input type="text" value="{{ $referralLink }}" id="referral-link" readonly>
                <button class="app-icon-btn" type="button" data-copy-value="{{ $referralLink }}" data-copy-message="@lang('Referral link copied')" aria-label="@lang('Copy referral link')">
                    <i class="las la-copy"></i>
                </button>
            </div>

            <div class="app-hero-actions">
                <button class="app-btn app-btn-primary" type="button"
                    data-share-link="{{ $referralLink }}"
                    data-share-copied-message="@lang('Referral link copied')">
                    <i class="las la-paper-plane"></i>
                    @lang('Share invite')
                </button>
                <button class="app-btn app-btn-secondary" type="button" data-copy-value="{{ $referralCode }}" data-copy-message="@lang('Referral code copied')">
                    <i class="las la-hashtag"></i>
                    {{ $referralCode }}
                </button>
            </div>
        </section>

        <div class="row app-grid mt-3">
            <div class="col-6 col-lg-3">
                <div class="app-stat" style="--tone: rgba(18, 104, 243, .12); --tone-color: #1268f3;">
                    <span class="app-stat-icon"><i class="las la-users"></i></span>
                    <label>@lang('Total network')</label>
                    <strong id="total-network">{{ $totalNetwork }}</strong>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="app-stat" style="--tone: rgba(22, 163, 74, .12); --tone-color: #16a34a;">
                    <span class="app-stat-icon"><i class="las la-coins"></i></span>
                    <label>@lang('Total earned')</label>
                    <strong>{{ showAmount($totalReferralCommission, 0) }}</strong>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="app-stat" style="--tone: rgba(20, 199, 214, .13); --tone-color: #0891b2;">
                    <span class="app-stat-icon"><i class="las la-calendar-week"></i></span>
                    <label>@lang('This month')</label>
                    <strong>{{ showAmount($monthlyReferralCommission, 0) }}</strong>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="app-stat" style="--tone: rgba(249, 115, 22, .12); --tone-color: #f97316;">
                    <span class="app-stat-icon"><i class="las la-layer-group"></i></span>
                    <label>@lang('Pay levels')</label>
                    <strong>{{ $levels->count() }}</strong>
                </div>
            </div>
        </div>

        <div class="row app-grid mt-1">
            <div class="col-lg-5">
                <div class="app-section-title">
                    <div>
                        <h2>@lang('Level overview')</h2>
                        <p>@lang('Choose a level to inspect active and inactive members.')</p>
                    </div>
                </div>

                <div class="app-card app-network-card">
                    <label class="app-field-label" for="network-level">@lang('Network level')</label>
                    <select class="app-form-control" id="network-level">
                        @if($levels->isNotEmpty())
                            @foreach($levels as $level)
                                <option value="{{ $level }}" @selected($level == $initialLevel)>@lang('Level') {{ $level }}</option>
                            @endforeach
                        @else
                            <option value="1">@lang('Level') 1</option>
                        @endif
                    </select>

                    <div class="app-network-ring mt-3">
                        <div>
                            <span id="lvl-network">@lang('Level') {{ $initialLevel }}</span>
                            <strong id="level-total">{{ optional($levelCounts->get($initialLevel))->total_count ?? 0 }}</strong>
                            <small>@lang('members in this level')</small>
                        </div>
                    </div>

                    <div class="app-network-split" id="level-user-counts">
                        @php
                            $initialCounts = $levelCounts->get($initialLevel);
                        @endphp
                        <div><span>@lang('Active')</span><strong>{{ optional($initialCounts)->active_count ?? 0 }}</strong></div>
                        <div><span>@lang('Inactive')</span><strong>{{ optional($initialCounts)->inactive_count ?? 0 }}</strong></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="app-section-title">
                    <div>
                        <h2>@lang('My downline')</h2>
                        <p>@lang('Inspect every member in your referral line by level, upline, plan status, and earned referral fee.')</p>
                    </div>
                </div>

                <div class="app-card app-downline-control">
                    <form method="GET" class="app-downline-filter">
                        <label class="app-field-label" for="network-level-filter">@lang('Show level')</label>
                        <select class="app-form-control" id="network-level-filter" name="network_level">
                            <option value="">@lang('All levels')</option>
                            @if($levels->isNotEmpty())
                                @foreach($levels as $level)
                                    <option value="{{ $level }}" @selected((string) $selectedNetworkLevel === (string) $level)>@lang('Level') {{ $level }}</option>
                                @endforeach
                            @else
                                <option value="1">@lang('Level') 1</option>
                            @endif
                        </select>
                    </form>
                    <div class="app-downline-summary">
                        <div>
                            <span>@lang('Active')</span>
                            <strong>{{ $networkSummary['active'] }}</strong>
                        </div>
                        <div>
                            <span>@lang('No plan')</span>
                            <strong>{{ $networkSummary['inactive'] }}</strong>
                        </div>
                        <div>
                            <span>@lang('Pending')</span>
                            <strong>{{ $networkSummary['pending'] }}</strong>
                        </div>
                        <div>
                            <span>@lang('Earned')</span>
                            <strong>{{ showAmount($networkSummary['commission'], 0) }}</strong>
                        </div>
                    </div>
                </div>

                <div class="app-table-shell mt-3">
                    <div class="table-responsive">
                        <table class="table custom--table mb-0">
                            <thead>
                                <tr>
                                    <th>@lang('Member')</th>
                                    <th>@lang('Level')</th>
                                    <th>@lang('Upline')</th>
                                    <th>@lang('Plan')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Referral Fee')</th>
                                    <th>@lang('Joined')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($networkMembers))
                                    @foreach($networkMembers as $member)
                                        @php
                                            $approval = $member->approval_status ?: 'approved';
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ censorReferrerName($member->firstname ?? null, $member->lastname ?? null, $member->username ?? null, '-') }}</strong>
                                                <span class="d-block text-muted">{{ censorReferrerEmail($member->email ?? null, '-') }}</span>
                                            </td>
                                            <td><span class="app-status-pill is-level">@lang('Level') {{ $member->level }}</span></td>
                                            <td>
                                                @php
                                                    $referrerName = trim(($member->referrer_firstname ?? '') . ' ' . ($member->referrer_lastname ?? ''));
                                                    if ($referrerName === '') {
                                                        $referrerName = $member->referrer_username ?? '';
                                                    }
                                                @endphp
                                                <strong>{{ censorReferrerName($member->referrer_firstname ?? null, $member->referrer_lastname ?? null, $referrerName !== '' ? $referrerName : null, '-') }}</strong>
                                                <span class="d-block text-muted">{{ censorReferrerEmail($member->referrer_email ?? null, '-') }}</span>
                                            </td>
                                            <td>{{ $member->plan_name ?: __('No plan') }}</td>
                                            <td>
                                                <span class="app-status-pill {{ (int) $member->status === 1 && $approval === 'approved' ? 'is-active' : ($approval === 'pending' ? 'is-pending' : 'is-muted') }}">
                                                    {{ $approval === 'approved' ? ((int) $member->status === 1 ? __('Active') : __('Disabled')) : __(ucfirst($approval)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ showAmount($member->total_commission, 0) }}</strong>
                                                <span class="d-block text-muted">{{ (int) $member->commission_count }} @lang('records')</span>
                                            </td>
                                            <td>{{ showDateTime($member->created_at, 'M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7">
                                            <div class="app-empty-state">@lang('No members found in this referral line yet.')</div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="app-pagination mt-3">{{ $networkMembers->links() }}</div>

                <div class="app-section-title">
                    <div>
                        <h2>@lang('Direct members')</h2>
                        <p>@lang('Newest members who joined with your referral code.')</p>
                    </div>
                </div>

                <div class="app-list-card">
                    @if($directReferrals->isNotEmpty())
                        @foreach($directReferrals as $member)
                            <div class="app-list-item">
                                <span class="app-list-icon"><i class="las la-user"></i></span>
                                <div class="app-list-body">
                                    <strong>{{ censorReferrerName($member->firstname ?? null, $member->lastname ?? null, $member->username ?? null, '-') }}</strong>
                                    <span>{{ censorReferrerEmail($member->email ?? null, '-') }} - {{ showDateTime($member->created_at, 'M d, Y') }}</span>
                                </div>
                                <span class="app-status-pill {{ $member->plan_id ? 'is-active' : 'is-muted' }}">
                                    {{ $member->plan_id ? __('Active') : __('No plan') }}
                                </span>
                            </div>
                        @endforeach
                    @else
                        <div class="app-empty-state">@lang('No direct members yet. Share your invite link to start building your network.')</div>
                    @endif
                </div>

                <div class="app-section-title">
                    <div>
                        <h2>@lang('Referral fee ledger')</h2>
                        <p>@lang('Every referral fee credited to your account.')</p>
                    </div>
                </div>

                <div class="app-list-card">
                    @if(count($commissions))
                        @foreach($commissions as $commission)
                            <div class="app-list-item">
                                <span class="app-list-icon"><i class="las la-level-up-alt"></i></span>
                                <div class="app-list-body">
                                    <strong>+{{ showAmount($commission->amount) }} - @lang('Level') {{ $commission->level }}</strong>
                                    <span>{{ censorReferrerName(optional($commission->sourceUser)->firstname, optional($commission->sourceUser)->lastname, optional($commission->sourceUser)->username ?? __('Member')) }} - {{ optional($commission->plan)->name ?? __('Package') }} - {{ optional($commission->created_at)->format('M d, Y H:i') }}</span>
                                </div>
                                <span class="app-amount is-plus">{{ number_format((float) $commission->percent, 2) }}%</span>
                            </div>
                        @endforeach
                    @else
                        <div class="app-empty-state">@lang('No referral fee has been credited yet.')</div>
                    @endif
                </div>

                <div class="app-pagination mt-3">{{ $commissions->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    (function() {
        "use strict";

        const levelSelect = document.getElementById('network-level');
        const totalNetwork = document.getElementById('total-network');
        const levelTitle = document.getElementById('lvl-network');
        const levelTotal = document.getElementById('level-total');
        const levelCounts = document.getElementById('level-user-counts');
        const networkLevelFilter = document.getElementById('network-level-filter');

        levelSelect?.addEventListener('change', function() {
            levelStatusShow(this.value);
        });

        networkLevelFilter?.addEventListener('change', function() {
            if (!this.value) {
                window.location.href = "{{ route('user.referrals') }}";
                return;
            }

            this.form.submit();
        });

        async function levelStatusShow(level) {
            levelTitle.textContent = `Level ${level}`;
            levelTotal.textContent = '...';
            levelCounts.innerHTML = `<div><span>${@json(__('Active'))}</span><strong>...</strong></div><div><span>${@json(__('Inactive'))}</span><strong>...</strong></div>`;

            try {
                const response = await fetch("{{ route('user.level.count') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ level }),
                });

                const data = await response.json();
                if (!response.ok) throw new Error(data.error || 'Network level load failed');

                const levelData = (data.levels || []).find((item) => Number(item.level) === Number(level)) || {};
                const active = Number(levelData.active_count || 0);
                const inactive = Number(levelData.inactive_count || 0);

                totalNetwork.textContent = data.total_network || 0;
                levelTotal.textContent = active + inactive;
                levelCounts.innerHTML = `<div><span>${@json(__('Active'))}</span><strong>${active}</strong></div><div><span>${@json(__('Inactive'))}</span><strong>${inactive}</strong></div>`;
            } catch (error) {
                notify('error', error.message);
            }
        }

    })();
</script>
@endpush
