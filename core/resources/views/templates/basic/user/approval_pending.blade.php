@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="app-page">
        <div class="app-container">
            @include($activeTemplate . 'partials.app_topbar', [
                'backUrl' => route('user.logout'),
                'brandTitle' => __('Account review'),
            ])

            <section class="app-hero">
                <span class="app-eyebrow">
                    <i class="las la-user-shield"></i>
                    @lang('Account review')
                </span>

                @if($user->approval_status === 'rejected')
                    <h1>@lang('Your account needs attention')</h1>
                    <p>@lang('The admin team rejected this registration. Please contact support if you believe this should be reviewed again.')</p>
                    @if($user->rejection_reason)
                        <div class="app-list-card mt-3">
                            <div class="app-list-item">
                                <span class="app-list-icon"><i class="las la-comment-dots"></i></span>
                                <div class="app-list-body">
                                    <strong>@lang('Admin note')</strong>
                                    <span>{{ $user->rejection_reason }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <h1>@lang('Waiting for admin approval')</h1>
                    <p>@lang('Your registration was received successfully. An admin must approve your account before deposits, withdrawals, package purchases, and referral features are available.')</p>
                @endif

                <div class="app-pills">
                    <span class="app-pill">
                        <i class="las la-envelope"></i>
                        {{ $user->email }}
                    </span>
                    <span class="app-pill">
                        <i class="las la-clock"></i>
                        {{ ucfirst($user->approval_status ?: 'pending') }}
                    </span>
                </div>

                <div class="app-hero-actions">
                    <a class="app-btn app-btn-primary" href="{{ route('ticket.open') }}">
                        <i class="las la-headset"></i>
                        @lang('Contact support')
                    </a>
                    <a class="app-btn app-btn-secondary" href="{{ route('user.logout') }}">
                        <i class="las la-sign-out-alt"></i>
                        @lang('Log out')
                    </a>
                </div>
            </section>
        </div>
    </div>
@endsection
