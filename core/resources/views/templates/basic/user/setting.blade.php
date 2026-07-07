@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="app-page">
        <div class="app-container">
            <div class="app-topbar">
                <a class="app-icon-btn" href="{{ route('user.home') }}" aria-label="@lang('Back to dashboard')">
                    <i class="las la-arrow-left"></i>
                </a>
                <a class="app-brand" href="{{ route('user.home') }}">
                    <img src="{{ siteLogo() }}" alt="To-app">
                    <span>@lang('Settings')</span>
                </a>
                <a class="app-icon-btn" href="{{ route('ticket.open') }}" aria-label="@lang('Support')">
                    <i class="las la-headset"></i>
                </a>
            </div>

            <section class="app-hero">
                <span class="app-eyebrow">
                    <i class="las la-user-cog"></i>
                    @lang('Account center')
                </span>
                <h1>{{ $user->firstname }} {{ $user->lastname }}</h1>
                <p>@lang('Manage your profile, security, withdrawal options, history, and support from one place.')</p>

                <div class="app-pills">
                    <span class="app-pill">
                        <i class="las la-envelope"></i>
                        {{ $user->email }}
                    </span>
                    @if ($user->mobile)
                        <span class="app-pill">
                            <i class="las la-phone"></i>
                            {{ $user->mobile }}
                        </span>
                    @endif
                </div>
            </section>

            <div class="app-section-title">
                <div>
                    <h2>@lang('Manage account')</h2>
                    <p>@lang('Everything important is grouped by action so the page feels less like a raw menu.')</p>
                </div>
            </div>

            <div class="app-list-card">
                <a class="app-list-item" href="{{ route('user.profile.setting') }}">
                    <span class="app-list-icon"><i class="las la-user"></i></span>
                    <div class="app-list-body">
                        <strong>@lang('Personal information')</strong>
                        <span>@lang('Name, phone number, and profile details')</span>
                    </div>
                    <i class="las la-angle-right"></i>
                </a>

                <a class="app-list-item" href="{{ route('user.change.password') }}">
                    <span class="app-list-icon"><i class="las la-lock"></i></span>
                    <div class="app-list-body">
                        <strong>@lang('Change password')</strong>
                        <span>@lang('Update your login credentials safely')</span>
                    </div>
                    <i class="las la-angle-right"></i>
                </a>

                <a class="app-list-item" href="{{ route('user.bank.setting') }}">
                    <span class="app-list-icon"><i class="las la-university"></i></span>
                    <div class="app-list-body">
                        <strong>@lang('Withdraw methods')</strong>
                        <span>@lang('Save PayPal or bank details for payouts')</span>
                    </div>
                    <i class="las la-angle-right"></i>
                </a>

                <a class="app-list-item" href="{{ route('user.transactions') }}">
                    <span class="app-list-icon"><i class="las la-receipt"></i></span>
                    <div class="app-list-body">
                        <strong>@lang('Transaction history')</strong>
                        <span>@lang('Review credits, debits, and references')</span>
                    </div>
                    <i class="las la-angle-right"></i>
                </a>

                @if(auth()->user()->agent_id)
                <a class="app-list-item" href="{{ route('user.agent.chat') }}">
                    <span class="app-list-icon"><i class="las la-user-tie"></i></span>
                    <div class="app-list-body">
                        <strong>@lang('My Agent')</strong>
                        <span>@lang('Private chat with your assigned agent')</span>
                    </div>
                    <i class="las la-angle-right"></i>
                </a>
                @endif

                <a class="app-list-item" href="{{ route('ticket.index') }}">
                    <span class="app-list-icon"><i class="las la-comments"></i></span>
                    <div class="app-list-body">
                        <strong>@lang('Support tickets')</strong>
                        <span>@lang('Follow up on previous support requests')</span>
                    </div>
                    <i class="las la-angle-right"></i>
                </a>

                <a class="app-list-item" href="{{ route('user.call') }}">
                    <span class="app-list-icon"><i class="las la-headset"></i></span>
                    <div class="app-list-body">
                        <strong>@lang('Contact support')</strong>
                        <span>@lang('Open live help if you need a human')</span>
                    </div>
                    <i class="las la-angle-right"></i>
                </a>

                <a class="app-list-item" href="{{ route('user.logout') }}">
                    <span class="app-list-icon" style="color:#dc2626; background: rgba(220, 38, 38, .1);"><i class="las la-sign-out-alt"></i></span>
                    <div class="app-list-body">
                        <strong>@lang('Logout')</strong>
                        <span>@lang('End this session on the current device')</span>
                    </div>
                    <i class="las la-angle-right"></i>
                </a>
            </div>
        </div>
    </div>
@endsection
