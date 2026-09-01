@extends($activeTemplate.'layouts.frontend')

@include($activeTemplate . 'partials.auth_ui')

@section('content')
    @php
        $cooldownSeconds = max(0, (int) optional($user->ver_code_send_at)->addMinutes(2)->timestamp - time());
    @endphp
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
                    <span class="auth-eyebrow">@lang('Email verification')</span>
                    <h1 class="auth-title">@lang('Enter your verification code')</h1>
                    <p class="auth-copy">
                        @lang('We sent a 6-digit code to')
                        <strong>{{ showEmailAddress($user->email) }}</strong>
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

                    <form method="POST" action="{{ route('user.verify.email') }}" class="auth-form auth-enhanced-form submit-form">
                        @csrf

                        <div class="auth-field">
                            @include($activeTemplate.'partials.verification_code')
                            <p class="auth-help">@lang('Check junk or spam if the email does not arrive within a few minutes.')</p>
                        </div>

                        <button type="submit" class="auth-submit" data-loading-text="@lang('Verifying...')">
                            @lang('Verify Email')
                        </button>
                    </form>

                    <div class="auth-footer-text mt-3">
                        <p class="mb-2">@lang('Did not get a code?')</p>
                        <form method="POST" action="{{ route('user.send.verify.code', 'email') }}" id="resend-form">
                            @csrf
                            <button type="submit" class="auth-link border-0 bg-transparent p-0 resend-btn" id="resend-btn" @if($cooldownSeconds > 0) disabled @endif>
                                @lang('Resend code')
                            </button>
                            <span id="resend-countdown" class="text-muted ms-1 @if($cooldownSeconds <= 0) d-none @endif">
                                (@lang('try again in') <span id="countdown">{{ $cooldownSeconds }}</span>s)
                            </span>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
(function() {
    var distance = Number(@json($cooldownSeconds));
    var countdownEl = document.getElementById('countdown');
    var countdownWrap = document.getElementById('resend-countdown');
    var resendBtn = document.getElementById('resend-btn');

    if (distance <= 0 || !countdownEl || !resendBtn) {
        return;
    }

    var timer = setInterval(function() {
        distance--;
        countdownEl.textContent = distance;
        if (distance <= 0) {
            clearInterval(timer);
            if (countdownWrap) {
                countdownWrap.classList.add('d-none');
            }
            resendBtn.disabled = false;
        }
    }, 1000);
})();
</script>
@endpush
