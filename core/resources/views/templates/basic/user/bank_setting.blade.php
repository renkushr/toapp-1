@extends($activeTemplate . 'layouts.master')

@section('content')
@php
    $selectedMethod = old('payout_method', $user->payout_method ?: 'paypal');
    $paypalContact = old(
        'payout_paypal_contact',
        $user->payout_paypal_contact
            ?: ($user->payout_paypal_phone && !$user->payout_paypal_email ? 'phone' : 'email')
    );
@endphp
<div class="app-page app-payout-page">
    <div class="app-container">
        <div class="app-topbar">
            <a class="app-icon-btn" href="{{ route('user.setting') }}" aria-label="@lang('Back to settings')">
                <i class="las la-arrow-left"></i>
            </a>
            <a class="app-brand" href="{{ route('user.home') }}">
                <img src="{{ siteLogo() }}" alt="To-App">
                <span>@lang('Withdraw Methods')</span>
            </a>
            <a class="app-icon-btn" href="{{ route('user.withdraw.history') }}" aria-label="@lang('Withdrawal history')">
                <i class="las la-history"></i>
            </a>
        </div>

        <section class="app-hero">
            <span class="app-eyebrow"><i class="las la-university"></i> @lang('Payout setup')</span>
            <h1>@lang('Choose how you want to receive withdrawals.')</h1>
            <p>@lang('Select PayPal or bank transfer, enter your payout details once, and we will use them for every withdrawal request.')</p>
            @if($user->hasPayoutSetup())
                <div class="app-pills">
                    <span class="app-pill">
                        <i class="las la-check-circle"></i>
                        @lang('Saved method'):
                        {{ $user->payout_method === 'paypal' ? __('PayPal') : __('Bank account') }}
                    </span>
                </div>
            @endif
        </section>

        <div class="app-section-title">
            <div>
                <h2>@lang('Payout details')</h2>
                <p>@lang('Your information is stored securely and attached to withdrawal requests.')</p>
            </div>
        </div>

        <div class="app-card">
            <form action="{{ route('user.bank.setting.update') }}" method="post" class="app-form-grid disableSubmission" id="payoutSetupForm">
                @csrf

                <div class="app-field">
                    <span>@lang('Withdraw through')</span>
                    <div class="app-payout-methods">
                        <label class="app-payout-method-card">
                            <input type="radio" name="payout_method" value="paypal" @checked($selectedMethod === 'paypal') required>
                            <span class="app-payout-method-check"><i class="las la-check"></i></span>
                            <span class="app-payout-method-icon"><i class="lab la-paypal"></i></span>
                            <strong>@lang('PayPal')</strong>
                            <span>@lang('Receive payouts to your PayPal email or phone')</span>
                            @if($paypalMethod)
                                <small>@lang('Limit') {{ showAmount($paypalMethod->min_limit) }} - {{ showAmount($paypalMethod->max_limit) }}</small>
                            @endif
                        </label>

                        <label class="app-payout-method-card">
                            <input type="radio" name="payout_method" value="bank" @checked($selectedMethod === 'bank') required>
                            <span class="app-payout-method-check"><i class="las la-check"></i></span>
                            <span class="app-payout-method-icon"><i class="las la-university"></i></span>
                            <strong>@lang('Bank account')</strong>
                            <span>@lang('Receive payouts by domestic bank transfer')</span>
                            @if($bankMethod)
                                <small>@lang('Limit') {{ showAmount($bankMethod->min_limit) }} - {{ showAmount($bankMethod->max_limit) }}</small>
                            @endif
                        </label>
                    </div>
                </div>

                <div class="app-payout-panel" data-panel="paypal" @if($selectedMethod !== 'paypal') hidden @endif>
                    <div class="mb-3">
                        <span class="app-field-label d-block mb-2">@lang('PayPal contact')</span>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="app-payout-contact-card">
                                    <input type="radio" name="payout_paypal_contact" value="email" @checked($paypalContact === 'email')>
                                    <span><i class="las la-envelope"></i> @lang('Email')</span>
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="app-payout-contact-card">
                                    <input type="radio" name="payout_paypal_contact" value="phone" @checked($paypalContact === 'phone')>
                                    <span><i class="las la-mobile"></i> @lang('Phone')</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <label class="app-field" data-paypal-field="email" @if($paypalContact !== 'email') hidden @endif>
                        <span>@lang('PayPal email')</span>
                        <input
                            type="email"
                            name="payout_paypal_email"
                            class="form-control"
                            value="{{ old('payout_paypal_email', $user->payout_paypal_email) }}"
                            placeholder="name@example.com"
                            autocomplete="email"
                        >
                        <small>@lang('Use the email linked to the PayPal account that should receive funds.')</small>
                    </label>

                    <label class="app-field" data-paypal-field="phone" @if($paypalContact !== 'phone') hidden @endif>
                        <span>@lang('PayPal phone number')</span>
                        <input
                            type="tel"
                            name="payout_paypal_phone"
                            class="form-control"
                            value="{{ old('payout_paypal_phone', $user->payout_paypal_phone) }}"
                            placeholder="+1 555 123 4567"
                            autocomplete="tel"
                            inputmode="tel"
                        >
                        <small>@lang('Use the phone number linked to your PayPal account.')</small>
                    </label>
                    @error('payout_paypal_email')<div class="text-danger">{{ $message }}</div>@enderror
                    @error('payout_paypal_phone')<div class="text-danger">{{ $message }}</div>@enderror
                    @error('payout_paypal_contact')<div class="text-danger">{{ $message }}</div>@enderror
                </div>

                <div class="app-payout-panel" data-panel="bank" @if($selectedMethod !== 'bank') hidden @endif>
                    <div class="app-form-split">
                        <label class="app-field">
                            <span>@lang('Account number') <em class="text-danger">*</em></span>
                            <input
                                type="text"
                                name="payout_account_number"
                                class="form-control"
                                value="{{ old('payout_account_number', $user->payout_account_number) }}"
                                placeholder="@lang('Account number')"
                                inputmode="numeric"
                                autocomplete="off"
                            >
                            <small>@lang('Your bank account number for deposits.')</small>
                        </label>

                        <label class="app-field">
                            <span>@lang('Routing number') <em class="text-danger">*</em></span>
                            <input
                                type="text"
                                name="payout_routing_number"
                                class="form-control"
                                value="{{ old('payout_routing_number', $user->payout_routing_number) }}"
                                placeholder="@lang('9-digit ABA routing number')"
                                inputmode="numeric"
                                maxlength="9"
                                autocomplete="off"
                            >
                            <small>@lang('USA 9-digit routing / ABA number.')</small>
                        </label>
                    </div>
                    @error('payout_account_number')<div class="text-danger">{{ $message }}</div>@enderror
                    @error('payout_routing_number')<div class="text-danger">{{ $message }}</div>@enderror
                </div>

                <div class="app-list-card">
                    <div class="app-list-item">
                        <span class="app-list-icon"><i class="las la-shield-alt"></i></span>
                        <div class="app-list-body">
                            <strong>@lang('Used for withdrawals')</strong>
                            <span>@lang('When you withdraw, the saved method must match the payout option you choose on the withdrawal page.')</span>
                        </div>
                    </div>
                </div>

                <button class="app-btn app-btn-primary w-100" type="submit">
                    <i class="las la-save"></i>
                    @lang('Save payout details')
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
.app-payout-contact-card {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 48px;
    padding: 10px 12px;
    border: 1px solid rgba(15, 23, 42, .12);
    border-radius: 14px;
    background: #fff;
    cursor: pointer;
    font-weight: 600;
}
.app-payout-contact-card input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}
.app-payout-contact-card:has(input:checked) {
    border-color: #1268f3;
    background: rgba(18, 104, 243, .08);
    color: #1268f3;
    box-shadow: inset 0 0 0 1px rgba(18, 104, 243, .35);
}
</style>
@endpush

@push('script')
<script>
    (function() {
        "use strict";

        const form = document.getElementById('payoutSetupForm');
        if (!form) return;

        const panels = form.querySelectorAll('.app-payout-panel');
        const methodInputs = form.querySelectorAll('input[name="payout_method"]');
        const contactInputs = form.querySelectorAll('input[name="payout_paypal_contact"]');
        const paypalEmail = form.querySelector('[name="payout_paypal_email"]');
        const paypalPhone = form.querySelector('[name="payout_paypal_phone"]');
        const paypalEmailWrap = form.querySelector('[data-paypal-field="email"]');
        const paypalPhoneWrap = form.querySelector('[data-paypal-field="phone"]');
        const bankFields = [
            form.querySelector('[name="payout_account_number"]'),
            form.querySelector('[name="payout_routing_number"]'),
        ];

        function syncPanels() {
            const selected = form.querySelector('input[name="payout_method"]:checked')?.value;
            const contact = form.querySelector('input[name="payout_paypal_contact"]:checked')?.value || 'email';

            panels.forEach(function(panel) {
                panel.hidden = panel.dataset.panel !== selected;
            });

            if (paypalEmailWrap) paypalEmailWrap.hidden = contact !== 'email';
            if (paypalPhoneWrap) paypalPhoneWrap.hidden = contact !== 'phone';

            if (paypalEmail) {
                paypalEmail.required = selected === 'paypal' && contact === 'email';
            }
            if (paypalPhone) {
                paypalPhone.required = selected === 'paypal' && contact === 'phone';
            }

            contactInputs.forEach(function(input) {
                input.required = selected === 'paypal';
            });

            bankFields.forEach(function(field) {
                if (field) {
                    field.required = selected === 'bank';
                }
            });
        }

        methodInputs.forEach(function(input) {
            input.addEventListener('change', syncPanels);
        });
        contactInputs.forEach(function(input) {
            input.addEventListener('change', syncPanels);
        });

        syncPanels();
    })();
</script>
@endpush
