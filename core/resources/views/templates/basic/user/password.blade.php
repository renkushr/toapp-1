@extends($activeTemplate . 'layouts.master')

@section('content')
<div class="app-page">
    <div class="app-container">
        <div class="app-topbar">
            <a class="app-icon-btn" href="{{ route('user.setting') }}" aria-label="@lang('Back to settings')">
                <i class="las la-arrow-left"></i>
            </a>
            <a class="app-brand" href="{{ route('user.home') }}">
                <img src="{{ siteLogo() }}" alt="To-App">
                <span>@lang('Security')</span>
            </a>
            <a class="app-icon-btn" href="{{ route('user.twofactor') }}" aria-label="@lang('Two factor')">
                <i class="las la-shield-alt"></i>
            </a>
        </div>

        <section class="app-hero">
            <span class="app-eyebrow"><i class="las la-lock"></i> @lang('Password')</span>
            <h1>@lang('Change your password safely.')</h1>
            <p>@lang('Use a password you do not use elsewhere. Strong passwords protect deposits, withdrawals, and referral rewards.')</p>
        </section>

        <div class="app-section-title">
            <div>
                <h2>@lang('Update password')</h2>
                <p>@lang('Confirm your current password before setting a new one.')</p>
            </div>
        </div>

        <div class="app-card">
            <form action="{{ route('user.change.password.update') }}" method="post" class="app-form-grid">
                @csrf
                <label class="app-field app-password-field">
                    <span>@lang('Current Password')</span>
                    <input type="password" id="password-current" name="current_password" required autocomplete="current-password" class="form-control">
                    <button type="button" data-toggle-password="#password-current" aria-label="@lang('Show password')"><i class="las la-eye"></i></button>
                </label>

                <label class="app-field app-password-field">
                    <span>@lang('New Password')</span>
                    <input type="password" id="password-new" name="password" required autocomplete="new-password" class="form-control @if (gs('secure_password')) secure-password @endif">
                    <button type="button" data-toggle-password="#password-new" aria-label="@lang('Show password')"><i class="las la-eye"></i></button>
                </label>

                <label class="app-field app-password-field">
                    <span>@lang('Confirm New Password')</span>
                    <input type="password" id="password-confirm" name="password_confirmation" required autocomplete="new-password" class="form-control">
                    <button type="button" data-toggle-password="#password-confirm" aria-label="@lang('Show password')"><i class="las la-eye"></i></button>
                </label>

                <div class="app-list-card">
                    <div class="app-list-item">
                        <span class="app-list-icon"><i class="las la-info-circle"></i></span>
                        <div class="app-list-body">
                            <strong>@lang('Security tip')</strong>
                            <span>@lang('A longer password is safer. Avoid names, birthdays, and repeated passwords.')</span>
                        </div>
                    </div>
                </div>

                <button class="app-btn app-btn-primary w-100" type="submit">
                    <i class="las la-save"></i>
                    @lang('Save password')
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@if(gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif

@push('script')
<script>
    (function() {
        "use strict";

        document.querySelectorAll('[data-toggle-password]').forEach(function(button) {
            button.addEventListener('click', function() {
                const input = document.querySelector(this.dataset.togglePassword);
                const icon = this.querySelector('i');
                if (!input) return;

                const shouldShow = input.type === 'password';
                input.type = shouldShow ? 'text' : 'password';
                icon.classList.toggle('la-eye', !shouldShow);
                icon.classList.toggle('la-eye-slash', shouldShow);
            });
        });
    })();
</script>
@endpush
