@extends($activeTemplate . 'layouts.master')

@section('content')
    @php
        $dashboardTransactions = $latestTrx->take(4);
        $latestInsuranceChatApplication = \App\Models\InsuranceApplication::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [
                \App\Models\InsuranceApplication::STATUS_CHATTING,
                \App\Models\InsuranceApplication::STATUS_PAYMENT_PENDING,
                \App\Models\InsuranceApplication::STATUS_CONFIRMED,
            ])
            ->latest('id')
            ->first();
        $agentChatUrl = $latestInsuranceChatApplication
            ? route('user.plans.chat', $latestInsuranceChatApplication)
            : route('user.agent.chat');
        $agentChatReady = (bool) ($latestInsuranceChatApplication || $user->agent_id);
    @endphp
    <div class="app-page app-dashboard">
        <div class="app-container">
            <div class="app-topbar">
                <a class="app-icon-btn" href="{{ route('home') }}" aria-label="@lang('Back')">
                    <i class="las la-arrow-left"></i>
                </a>
                <a class="app-brand" href="{{ route('user.home') }}">
                    <img src="{{ siteLogo() }}" alt="To-app">
                    <span>To-app</span>
                </a>

                <div class="d-flex align-items-center gap-2">
                    <a class="app-icon-btn" href="{{ $agentChatUrl }}" aria-label="@lang('Chat with Agent')">
                        <i class="las la-comments"></i>
                    </a>
                    <a class="app-icon-btn" href="{{ route('user.setting') }}" aria-label="@lang('Open settings')">
                        <i class="las la-cog"></i>
                    </a>
                </div>
            </div>

            <a class="app-chat-banner" href="{{ $agentChatUrl }}">
                <span class="app-chat-banner-icon"><i class="las la-user-tie"></i></span>
                <span class="app-chat-banner-body">
                    <strong>@lang('Chat with Agent')</strong>
                    <span>
                        @if($agentChatReady)
                            @lang('Open your private conversation with your assigned agent.')
                        @else
                            @lang('Get matched with an agent after you choose a plan.')
                        @endif
                    </span>
                </span>
                <i class="las la-angle-right app-chat-banner-arrow"></i>
            </a>

            <section class="app-hero">
                <span class="app-eyebrow">
                    <i class="las la-star"></i>
                    @lang('Dashboard')
                </span>
                <h1>@lang('Hello'), {{ $user->firstname }}</h1>
                <p>@lang('Track your balance, active plans, payouts, and referral network in one clean workspace.')</p>

                <div class="app-pills">
                    <span class="app-pill">
                        <i class="las la-wallet"></i>
                        @lang('Wallet') {{ showAmount($user->balance) }}
                    </span>
                    <span class="app-pill">
                        <i class="las la-check-circle"></i>
                        @lang('Ready to withdraw') {{ showAmount($withdrawSummary['withdrawable_amount']) }}
                    </span>
                    <span class="app-pill">
                        <i class="las la-hourglass-half"></i>
                        @lang('Pending') {{ showAmount($withdrawSummary['pending_amount']) }}
                    </span>
                </div>

                <div class="app-hero-actions">
                    <a class="app-btn app-btn-primary" href="{{ $agentChatUrl }}">
                        <i class="las la-comments"></i>
                        @lang('Chat with Agent')
                    </a>
                    <a class="app-btn app-btn-secondary" href="{{ route('user.plans') }}">
                        <i class="las la-shield-alt"></i>
                        @lang('Find a plan')
                    </a>
                    <a class="app-btn app-btn-secondary" href="{{ route('user.withdraw.index') }}">
                        <i class="las la-money-bill-wave"></i>
                        @lang('Withdraw')
                    </a>
                    @include($activeTemplate . 'partials.mobile_shortcut')
                </div>
            </section>

            <div class="app-section-title">
                <div>
                    <h2>@lang('Your snapshot')</h2>
                    <p>@lang('The numbers that matter most right now.')</p>
                </div>
                <a class="app-link" href="{{ route('policy.pages', 'terms-of-service') }}">
                    @lang('Terms')
                    <i class="las la-arrow-right"></i>
                </a>
            </div>

            <div class="row app-grid">
                <div class="col-6 col-lg-3">
                    <div class="app-stat" style="--tone: rgba(22, 163, 74, .12); --tone-color: #16a34a;">
                        <span class="app-stat-icon"><i class="las la-check-circle"></i></span>
                        <label>@lang('Ready to withdraw')</label>
                        <strong>{{ showAmount($withdrawSummary['withdrawable_amount'], 0) }}</strong>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="app-stat" style="--tone: rgba(234, 179, 8, .14); --tone-color: #ca8a04;">
                        <span class="app-stat-icon"><i class="las la-hourglass-half"></i></span>
                        <label>@lang('Pending (< :days days)', ['days' => $withdrawSummary['holding_days']])</label>
                        <strong>{{ showAmount($withdrawSummary['pending_amount'], 0) }}</strong>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="app-stat" style="--tone: rgba(18, 104, 243, .12); --tone-color: #1268f3;">
                        <span class="app-stat-icon"><i class="las la-play-circle"></i></span>
                        <label>@lang('Running plans')</label>
                        <strong>{{ $runningInvestmentCount }}</strong>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="app-stat" style="--tone: rgba(22, 163, 74, .12); --tone-color: #16a34a;">
                        <span class="app-stat-icon"><i class="las la-arrow-down"></i></span>
                        <label>@lang('Deposits')</label>
                        <strong>{{ showAmount($totalDeposit, 0) }}</strong>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="app-stat" style="--tone: rgba(249, 115, 22, .12); --tone-color: #f97316;">
                        <span class="app-stat-icon"><i class="las la-arrow-up"></i></span>
                        <label>@lang('Withdrawn')</label>
                        <strong>{{ showAmount($totalWithdraw, 0) }}</strong>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="app-stat" style="--tone: rgba(20, 199, 214, .13); --tone-color: #0891b2;">
                        <span class="app-stat-icon"><i class="las la-users"></i></span>
                        <label>@lang('Referrals')</label>
                        <strong>{{ $directReferralCount }}</strong>
                    </div>
                </div>
            </div>

            <div class="row app-grid mt-1">
                <div class="col-lg-7">
                    <div class="app-section-title">
                        <div>
                            <h2>@lang('Recent activity')</h2>
                            <p>@lang('Latest balance movements from your account.')</p>
                        </div>
                        <a class="app-link" href="{{ route('user.transactions') }}">
                            @lang('View all')
                            <i class="las la-arrow-right"></i>
                        </a>
                    </div>

                    <div class="app-list-card">
                        @if($dashboardTransactions->isEmpty())
                            <div class="app-empty-state">
                                <i class="las la-receipt d-block fs-1 mb-2"></i>
                                @lang('No transactions yet. Your first activity will appear here.')
                            </div>
                        @else
                            @foreach($dashboardTransactions as $trx)
                                <div class="app-list-item">
                                    <span class="app-list-icon">
                                        <i class="las {{ $trx->trx_type == '+' ? 'la-plus' : 'la-minus' }}"></i>
                                    </span>
                                    <div class="app-list-body">
                                        <strong>{{ $trx->details }}</strong>
                                        <span>{{ showDateTime($trx->created_at, 'M d, Y h:i A') }}</span>
                                    </div>
                                    <div class="app-amount {{ $trx->trx_type == '+' ? 'is-plus' : 'is-minus' }}">
                                        {{ $trx->trx_type }}{{ showAmount($trx->amount) }}
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="app-section-title">
                        <div>
                            <h2>@lang('Account health')</h2>
                            <p>@lang('Small details that help users know what to do next.')</p>
                        </div>
                    </div>

                    <div class="app-list-card mb-3">
                        <div class="app-list-item">
                            <span class="app-list-icon"><i class="las la-shield-alt"></i></span>
                            <div class="app-list-body">
                                <strong>{{ $runningInvestmentCount > 0 ? __('Active') : __('Inactive') }}</strong>
                                <span>@lang('Current account status')</span>
                            </div>
                        </div>

                        <div class="app-list-item">
                            <span class="app-list-icon"><i class="las la-calendar-check"></i></span>
                            <div class="app-list-body">
                                <strong>{{ $nextReturnDate ? showDateTime($nextReturnDate, 'M d, Y') : __('No active payout') }}</strong>
                                <span>@lang('Next payout')</span>
                            </div>
                        </div>

                        <div class="app-list-item">
                            <span class="app-list-icon"><i class="las la-user-clock"></i></span>
                            <div class="app-list-body">
                                <strong>{{ showDateTime($user->created_at, 'M d, Y') }}</strong>
                                <span>@lang('Joined us since')</span>
                            </div>
                        </div>
                    </div>

                    <div class="app-card">
                        <div class="d-flex align-items-start gap-3">
                            <span class="app-list-icon"><i class="las la-share-alt"></i></span>
                            <div>
                                <h2 class="mb-1 fs-5 fw-bold">@lang('Referral code')</h2>
                                <p class="mb-0 text-muted">@lang('Invite friends and track your network from the referral page.')</p>
                            </div>
                        </div>

                        <div class="app-copy-box">
                            <input type="text" value="{{ $referralCode }}" id="referral-code" readonly>
                            <button class="app-icon-btn" type="button" data-copy-value="{{ $referralCode }}" data-copy-message="@lang('Referral code copied')" aria-label="@lang('Copy referral code')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>

                        <div class="app-hero-actions">
                            <button class="app-btn app-btn-primary" type="button"
                                data-share-link="{{ $referralLink }}"
                                data-share-copied-message="@lang('Referral link copied')">
                                <i class="las la-paper-plane"></i>
                                @lang('Share link')
                            </button>
                            <a class="app-btn app-btn-secondary" href="{{ route('user.referrals') }}">
                                <i class="las la-network-wired"></i>
                                @lang('My network')
                            </a>
                        </div>

                        <div class="app-list-card mt-3">
                            <div class="app-list-item">
                                <span class="app-list-icon"><i class="las la-coins"></i></span>
                                <div class="app-list-body">
                                    <strong>{{ showAmount($totalReferralCommission) }}</strong>
                                    <span>@lang('Total referral fee earned')</span>
                                </div>
                            </div>
                            @if($recentReferralCommissions->isNotEmpty())
                            @foreach($recentReferralCommissions as $commission)
                                <div class="app-list-item">
                                    <span class="app-list-icon"><i class="las la-level-up-alt"></i></span>
                                    <div class="app-list-body">
                                        <strong>+{{ showAmount($commission->amount) }} · Level {{ $commission->level }}</strong>
                                        <span>{{ optional($commission->sourceUser)->username ?? __('Member') }} · {{ optional($commission->plan)->name ?? __('Package') }}</span>
                                    </div>
                                </div>
                            @endforeach
                            @else
                                <div class="app-list-item">
                                    <span class="app-list-icon"><i class="las la-seedling"></i></span>
                                    <div class="app-list-body">
                                        <strong>@lang('No referral fee yet')</strong>
                                        <span>@lang('Referral fees appear here after referred members buy packages.')</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
