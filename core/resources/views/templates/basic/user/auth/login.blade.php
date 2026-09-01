@extends($activeTemplate.'layouts.frontend')

@include($activeTemplate . 'partials.auth_ui')

@section('content')
    <div class="auth-surface">
        <div class="auth-shell">
            <div class="auth-topbar">
                <a class="auth-back" href="{{ route('home') }}" aria-label="@lang('Back to home')">
                    <i class="las la-arrow-left"></i>
                </a>
                <a class="auth-link" href="{{ route('user.register') }}">@lang('Create account')</a>
            </div>

            <div class="auth-brand">
                <img src="{{ asset('assets/global/img/tologo.png') }}" alt="To-app logo">
            </div>

            <div class="auth-card">
                <div class="auth-card__header">
                    <span class="auth-eyebrow">@lang('Welcome back')</span>
                    <h1 class="auth-title">@lang('Log in to To-app')</h1>
                    <p class="auth-copy">@lang('Use your email or username to continue to your dashboard.')</p>
                </div>

                <div class="auth-card__body">
                    @if ($errors->any())
                        <div class="auth-error-summary">
                            @foreach ($errors->all() as $error)
                                <div>{{ __($error) }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form class="auth-form auth-enhanced-form verify-gcaptcha" action="{{ route('user.login') }}" method="post">
                        @csrf

                        <div class="auth-field">
                            <label class="auth-label" for="username">@lang('Email or Username')</label>
                            <input
                                id="username"
                                type="text"
                                name="username"
                                value="{{ old('username') }}"
                                class="auth-control"
                                placeholder="@lang('Email or username')"
                                autocomplete="username"
                                required
                            >
                        </div>

                        <div class="auth-field">
                            <div class="auth-label-row">
                                <label class="auth-label" for="password-toggle">@lang('Password')</label>
                                <a class="auth-link" href="{{ route('user.password.request') }}">@lang('Forgot password?')</a>
                            </div>
                            <div class="auth-password-wrap">
                                <input
                                    id="password-toggle"
                                    type="password"
                                    class="auth-control"
                                    name="password"
                                    placeholder="@lang('Password')"
                                    autocomplete="current-password"
                                    required
                                >
                                <button class="auth-password-toggle" type="button" data-target="#password-toggle" aria-label="@lang('Show password')">
                                    <i class="las la-eye"></i>
                                </button>
                            </div>
                        </div>

                        @include('partials.captcha')

                        <button type="submit" class="auth-submit" data-loading-text="@lang('Logging in...')">
                            @lang('Login')
                        </button>
                    </form>

                    @include($activeTemplate . 'partials.mobile_shortcut')

                    <p class="auth-footer-text">
                        @lang('New here?')
                        <a class="auth-link" href="{{ route('user.register') }}">@lang('Create an account')</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
