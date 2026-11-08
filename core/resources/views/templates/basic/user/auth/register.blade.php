@extends($activeTemplate.'layouts.frontend')

@include($activeTemplate . 'partials.auth_ui')

@section('content')
    <div class="auth-surface">
        <div class="auth-shell">
            <div class="auth-topbar">
                <a class="auth-back" href="{{ route('home') }}" aria-label="@lang('Back to home')">
                    <i class="las la-arrow-left"></i>
                </a>
                <a class="auth-link" href="{{ route('user.login') }}">@lang('Log In')</a>
            </div>

            <div class="auth-brand">
                <img src="{{ asset('assets/global/img/tologo.png') }}" alt="To-App logo">
            </div>

            <div class="auth-card">
                <div class="auth-card__header">
                    <span class="auth-eyebrow">@lang('Step 1 of 3')</span>
                    <h1 class="auth-title">@lang('Create your account')</h1>
                    <p class="auth-copy">@lang('Start with your name, email, and the referral code you received from an existing member.')</p>
                </div>

                <div class="auth-card__body">
                    <div class="auth-progress" aria-label="@lang('Registration progress')">
                        <span class="auth-step is-active"></span>
                        <span class="auth-step"></span>
                        <span class="auth-step"></span>
                    </div>

                    @if ($errors->any())
                        <div class="auth-error-summary">
                            @foreach ($errors->all() as $error)
                                <div>{{ __($error) }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form class="auth-form auth-enhanced-form" action="{{ route('user.register') }}" method="post">
                        @csrf

                        <div class="auth-field">
                            <label class="auth-label" for="firstname">@lang('First name')</label>
                            <input
                                id="firstname"
                                type="text"
                                name="firstname"
                                class="auth-control"
                                value="{{ data_get(session('user_register'), 'firstname', old('firstname')) }}"
                                placeholder="@lang('Your first name')"
                                autocomplete="given-name"
                                required
                            >
                        </div>

                        <div class="auth-field">
                            <label class="auth-label" for="lastname">@lang('Last name')</label>
                            <input
                                id="lastname"
                                type="text"
                                name="lastname"
                                class="auth-control"
                                value="{{ data_get(session('user_register'), 'lastname', old('lastname')) }}"
                                placeholder="@lang('Your last name')"
                                autocomplete="family-name"
                                required
                            >
                        </div>

                        <div class="auth-field">
                            <label class="auth-label" for="email">@lang('Email')</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                class="auth-control checkUser"
                                value="{{ data_get(session('user_register'), 'email', old('email')) }}"
                                placeholder="name@example.com"
                                autocomplete="email"
                                required
                            >
                            <p class="auth-help">@lang('Use an email you can access. It may be used for account verification and support.')</p>
                        </div>

                        <div class="auth-field">
                            <div class="auth-label-row">
                                <label class="auth-label" for="ref_by">@lang('Referral Code')</label>
                                <span class="auth-optional">@lang('Required')</span>
                            </div>
                            <input
                                id="ref_by"
                                type="text"
                                name="ref_by"
                                class="auth-control"
                                value="{{ data_get(session('user_register'), 'ref_by', old('ref_by', session('reference'))) }}"
                                placeholder="@lang('Enter your invitation code')"
                                required
                            >
                            <p class="auth-help">@lang('You need a valid referral code from an existing member to create an account.')</p>
                        </div>

                        <div class="auth-note">
                            <span class="auth-note__icon">i</span>
                            <span>@lang('Your information is used to prepare your account profile before choosing a plan or making transactions.')</span>
                        </div>

                        <button type="submit" class="auth-submit" data-loading-text="@lang('Checking...')">
                            @lang('Continue')
                        </button>
                    </form>

                    <p class="auth-footer-text">
                        @lang('Already have an account?')
                        <a class="auth-link" href="{{ route('user.login') }}">@lang('Log In')</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade auth-app-modal" id="existModalCenter" tabindex="-1" aria-labelledby="existModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="existModalTitle">@lang('Account already exists')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="@lang('Close')"></button>
                </div>
                <div class="modal-body">
                    @lang('This email is already connected to an account. You can log in instead, or use a different email to create a new account.')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light auth-modal-button" data-bs-dismiss="modal">@lang('Use another email')</button>
                    <a href="{{ route('user.login') }}" class="btn btn-primary auth-modal-button">@lang('Go to login')</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            let emailCheckTimer = null;
            let lastCheckedEmail = '';

            $('.checkUser').on('input focusout', function() {
                const value = $(this).val().trim();

                clearTimeout(emailCheckTimer);

                if (!value || value === lastCheckedEmail || !value.includes('@')) {
                    return;
                }

                emailCheckTimer = setTimeout(function() {
                    lastCheckedEmail = value;

                    $.post('{{ route('user.checkUser') }}', {
                        email: value,
                        _token: '{{ csrf_token() }}'
                    }, function(response) {
                        if (response.data != false) {
                            $('#existModalCenter').modal('show');
                        }
                    });
                }, 450);
            });
        })(jQuery);
    </script>
@endpush
