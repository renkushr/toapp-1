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
                <img src="{{ asset('assets/global/img/tologo.png') }}" alt="To-App logo">
            </div>

            <div class="auth-card">
                <div class="auth-card__header">
                    <span class="auth-eyebrow">@lang('Security')</span>
                    <h1 class="auth-title">@lang('2FA Verification')</h1>
                    <p class="auth-copy">@lang('Enter the 6-digit code from your authenticator app.')</p>
                </div>

                <div class="auth-card__body">
                    @if ($errors->any())
                        <div class="auth-error-summary">
                            @foreach ($errors->all() as $error)
                                <div>{{ __($error) }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user.2fa.verify') }}" class="auth-form auth-enhanced-form submit-form">
                        @csrf
                        <div class="auth-field">
                            @include($activeTemplate.'partials.verification_code')
                        </div>
                        <button type="submit" class="auth-submit">@lang('Verify')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
