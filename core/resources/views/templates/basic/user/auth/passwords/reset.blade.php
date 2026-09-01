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
                    <span class="auth-eyebrow">@lang('New password')</span>
                    <h1 class="auth-title">@lang('Choose a new password')</h1>
                    <p class="auth-copy">@lang('Make it strong and different from passwords you use elsewhere.')</p>
                </div>

                <div class="auth-card__body">
                    @if ($errors->any())
                        <div class="auth-error-summary">
                            @foreach ($errors->all() as $error)
                                <div>{{ __($error) }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user.password.update') }}" class="auth-form auth-enhanced-form">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="auth-field">
                            <label class="auth-label" for="password-toggle">@lang('New password')</label>
                            <div class="auth-password-wrap">
                                <input
                                    id="password-toggle"
                                    type="password"
                                    class="auth-control @if(gs('secure_password')) secure-password @endif"
                                    name="password"
                                    placeholder="@lang('New password')"
                                    autocomplete="new-password"
                                    required
                                    autofocus
                                >
                                <button class="auth-password-toggle" type="button" data-target="#password-toggle" aria-label="@lang('Show password')">
                                    <i class="las la-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="auth-field">
                            <label class="auth-label" for="password-toggleconfirm">@lang('Confirm password')</label>
                            <div class="auth-password-wrap">
                                <input
                                    id="password-toggleconfirm"
                                    type="password"
                                    class="auth-control"
                                    name="password_confirmation"
                                    placeholder="@lang('Repeat new password')"
                                    autocomplete="new-password"
                                    required
                                >
                                <button class="auth-password-toggle" type="button" data-target="#password-toggleconfirm" aria-label="@lang('Show password')">
                                    <i class="las la-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="auth-submit" data-loading-text="@lang('Updating...')">
                            @lang('Update Password')
                        </button>
                    </form>

                    <p class="auth-footer-text">
                        <a class="auth-link" href="{{ route('user.login') }}">@lang('Back to login')</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@if(gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif
