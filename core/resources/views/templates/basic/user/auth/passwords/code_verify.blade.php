@extends($activeTemplate.'layouts.frontend')

@include($activeTemplate . 'partials.auth_ui')

@section('content')
    <div class="auth-surface">
        <div class="auth-shell">
            <div class="auth-topbar">
                <a class="auth-back" href="{{ route('user.password.request') }}" aria-label="@lang('Back')">
                    <i class="las la-arrow-left"></i>
                </a>
                <a class="auth-link" href="{{ route('user.login') }}">@lang('Login')</a>
            </div>

            <div class="auth-brand">
                <img src="{{ asset('assets/global/img/tologo.png') }}" alt="To-app logo">
            </div>

            <div class="auth-card">
                <div class="auth-card__header">
                    <span class="auth-eyebrow">@lang('Verification')</span>
                    <h1 class="auth-title">@lang('Enter recovery code')</h1>
                    <p class="auth-copy">
                        @lang('We sent a 6-digit code to')
                        <strong>{{ $email }}</strong>
                    </p>
                </div>

                <div class="auth-card__body">
                    @if ($errors->any())
                        <div class="auth-error-summary">
                            @foreach ($errors->all() as $error)
                                <div>{{ __($error) }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user.password.verify.code') }}" class="auth-form auth-enhanced-form">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">

                        <div class="auth-field">
                            @include($activeTemplate.'partials.verification_code')
                            <p class="auth-help">@lang('Check junk or spam if the email does not arrive within a few minutes.')</p>
                        </div>

                        <button type="submit" class="auth-submit" data-loading-text="@lang('Verifying...')">
                            @lang('Verify Code')
                        </button>
                    </form>

                    <p class="auth-footer-text">
                        @lang('Did not get a code?')
                        <a class="auth-link" href="{{ route('user.password.request') }}">@lang('Request again')</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
