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
        $chatUnread = app(\App\Services\AgentChatService::class)->unreadCountForUser($user);
    @endphp
    <div class="app-page app-dashboard">
        <div class="app-container">
            <div class="app-topbar">
                <a class="app-icon-btn" href="{{ route('home') }}" aria-label="@lang('Back')">
                    <i class="las la-arrow-left"></i>
                </a>
                <a class="app-brand" href="{{ route('user.home') }}">
                    <img src="{{ siteLogo() }}" alt="To-App">
                    <span>To-App</span>
                </a>

                <div class="d-flex align-items-center gap-2">
                    @include('partials.language_switcher', ['variant' => 'compact'])
                    <a class="app-icon-btn user-chat-notify-target {{ ($chatUnread ?? 0) ? 'has-unread' : '' }}"
                       href="{{ $agentChatUrl }}"
                       aria-label="@lang('Chat with Agent')"
                       title="@lang('Chat with Agent')">
                        <i class="las la-comments"></i>
                        <span class="user-chat-unread-badge user-chat-unread-count {{ ($chatUnread ?? 0) ? '' : 'd-none' }}">{{ ($chatUnread ?? 0) > 99 ? '99+' : ($chatUnread ?? 0) }}</span>
                    </a>
                    <a class="app-icon-btn" href="{{ route('user.setting') }}" aria-label="@lang('Open settings')">
                        <i class="las la-cog"></i>
                    </a>
                </div>
            </div>

            <a class="app-chat-banner user-chat-notify-target {{ $chatUnread ? 'has-unread' : '' }}" href="{{ $agentChatUrl }}">
                <span class="app-chat-banner-icon"><i class="las la-user-tie"></i></span>
                <span class="app-chat-banner-body">
                    <strong>
                        @lang('Chat with Agent')
                        <span class="user-chat-banner-pill user-chat-unread-count user-chat-unread-badge {{ $chatUnread ? '' : 'd-none' }}">{{ $chatUnread > 99 ? '99+' : $chatUnread }}</span>
                    </strong>
                    <span>
                        @if($chatUnread > 0)
                            @lang('You have unread messages from your agent.')
                        @elseif($agentChatReady)
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
                    <a class="app-btn app-btn-secondary" href="{{ route('user.plans.mine') }}">
                        <i class="las la-list"></i>
                        @lang('My Packages')
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
                <a class="app-link" href="#policyModal-terms-of-service" data-bs-toggle="modal" data-bs-target="#policyModal-terms-of-service" role="button">
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

            @php
                $dashboardSocial = collect(config('toapp.social', []))->filter();
                $dashboardSupportEmail = trim((string) (gs('support_email') ?: config('toapp.support_email', 'tonkowtek@to-app.com')));
                $dashboardSocialIcons = [
                    'facebook' => '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M14 9h3V6h-3c-1.7 0-3 1.3-3 3v2H9v3h2v7h3v-7h2.2l.8-3H14V9z"/></svg>',
                    'instagram' => '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M7 3h10a4 4 0 0 1 4 4v10a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4zm5 4.5A4.5 4.5 0 1 0 16.5 12 4.5 4.5 0 0 0 12 7.5zm0 7.2A2.7 2.7 0 1 1 14.7 12 2.7 2.7 0 0 1 12 14.7zM17.8 6.2a1.1 1.1 0 1 0 1.1 1.1 1.1 1.1 0 0 0-1.1-1.1z"/></svg>',
                    'tiktok' => '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M14.5 3h2.1c.2 1.7 1.3 3.2 2.9 4v2.3a7.2 7.2 0 0 1-2.9-1v6.1a5.5 5.5 0 1 1-5.5-5.5c.3 0 .6 0 .9.1v2.5a3 3 0 1 0 2.1 2.9V3z"/></svg>',
                    'threads' => '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M16.2 11.2c-.1-2.2-1.3-3.7-3.5-3.8-2.5-.1-4.1 1.7-4.1 4.4 0 2.8 1.6 4.6 4.2 4.6 1.6 0 2.9-.6 3.8-1.6l-1.2-1.1c-.6.7-1.5 1.1-2.5 1.1-1.6 0-2.6-1.1-2.6-3 0-1.8 1-2.9 2.5-2.9 1.1 0 1.8.5 2.1 1.5h-2v1.8h3.9c.1-.4.1-.8.1-1.2 0-.5 0-.9-.1-1.3h2.1c.1.5.1 1 .1 1.5 0 .7-.1 1.3-.2 1.9-.5 2.1-2.1 3.5-4.7 3.5-3.5 0-5.8-2.4-5.8-5.9S8.7 5 12.1 5c3.1 0 5.1 1.8 5.4 4.5h-2.1c-.2-1.5-1.3-2.7-3.3-2.7-2.2 0-3.5 1.6-3.5 4.1s1.3 4.1 3.6 4.1c1.5 0 2.5-.6 3-1.6.2.5.5.9.9 1.2.9.8 2.1 1.2 3.5 1.2v2c-1.8 0-3.3-.6-4.5-1.6-.4.3-.8.5-1.3.7-.8.3-1.7.4-2.6.4-4.2 0-7-2.9-7-6.8S7.8 3 12.1 3c4 0 6.7 2.5 7 6.4h-2.9z"/></svg>',
                    'x' => '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M4 4h4.1l4 5.5L16.8 4H20l-6.3 7.2L20.5 20H16.4l-4.4-6-5 6H3.2l6.7-7.7L4 4z"/></svg>',
                ];
                $dashboardSocialItems = [
                    ['key' => 'facebook', 'label' => __('Facebook')],
                    ['key' => 'instagram', 'label' => __('Instagram')],
                    ['key' => 'tiktok', 'label' => __('TikTok')],
                    ['key' => 'threads', 'label' => __('Threads')],
                    ['key' => 'x', 'label' => __('X')],
                ];
            @endphp
            @if($dashboardSocial->isNotEmpty() || $dashboardSupportEmail !== '')
                <div class="app-section-title mt-1">
                    <div>
                        <h2>@lang('Follow us')</h2>
                        <p>@lang('Stay connected on social media.')</p>
                    </div>
                    @if($dashboardSupportEmail !== '')
                        <a class="app-link" href="mailto:{{ $dashboardSupportEmail }}">
                            @lang('Email')
                            <i class="las la-arrow-right"></i>
                        </a>
                    @endif
                </div>

                <div class="app-card app-social-card mb-3">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <span class="app-list-icon"><i class="las la-share-alt"></i></span>
                        <div>
                            <h2 class="mb-1 fs-5 fw-bold">@lang('Contact')</h2>
                            <p class="mb-0 text-muted">
                                @if($dashboardSupportEmail !== '')
                                    <a class="app-social-card__email" href="mailto:{{ $dashboardSupportEmail }}">{{ $dashboardSupportEmail }}</a>
                                @else
                                    @lang('Stay connected on social media.')
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="app-social-card__grid" role="list">
                        @foreach($dashboardSocialItems as $item)
                            @if($dashboardSocial->has($item['key']))
                                <a class="app-social-card__item"
                                   role="listitem"
                                   href="{{ $dashboardSocial[$item['key']] }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   aria-label="{{ $item['label'] }}">
                                    <span class="app-social-card__icon">{!! $dashboardSocialIcons[$item['key']] !!}</span>
                                    <span class="app-social-card__label">{{ $item['label'] }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

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

    @include($activeTemplate . 'partials.policy_modals')
@endsection
