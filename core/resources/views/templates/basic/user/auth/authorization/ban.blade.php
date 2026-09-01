@extends($activeTemplate.'layouts.frontend')

@include($activeTemplate . 'partials.auth_ui')

@section('content')
    <div class="auth-surface">
        <div class="auth-shell">
            <div class="auth-topbar">
                <a class="auth-back" href="{{ route('user.logout') }}" aria-label="@lang('Back')">
                    <i class="las la-arrow-left"></i>
                </a>
                <a class="auth-link" href="{{ route('user.logout') }}">@lang('Log out')</a>
            </div>

            <div class="auth-brand">
                <img src="{{ asset('assets/global/img/tologo.png') }}" alt="To-app logo">
            </div>

            <div class="auth-card">
                <div class="auth-card__header">
                    <span class="auth-eyebrow">@lang('Account status')</span>
                    <h1 class="auth-title">@lang('You are banned')</h1>
                    <p class="auth-copy">@lang('Your account access has been restricted. Please contact support if you need help.')</p>
                </div>
                <div class="auth-card__body">
                    @if(!empty($user->ban_reason))
                        <div class="auth-error-summary">
                            <div><strong>@lang('Reason'):</strong> {{ $user->ban_reason }}</div>
                        </div>
                    @endif
                    <a class="auth-submit d-inline-flex justify-content-center text-decoration-none" href="{{ route('user.logout') }}">
                        @lang('Log out')
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
