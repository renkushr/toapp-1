@extends($activeTemplate . 'layouts.master')

@section('content')
@php
    $selectedMethod = old('payout_method', $user->payout_method ?: 'paypal');
@endphp
<div class="app-page app-payout-page">
    <div class="app-container">
        <div class="app-topbar">
            <a class="app-icon-btn" href="{{ route('user.setting') }}" aria-label="@lang('Back to settings')">
                <i class="las la-arrow-left"></i>
            </a>
            <a class="app-brand" href="{{ route('user.home') }}">
                <img src="{{ siteLogo() }}" alt="To-app">
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
                            <span>@lang('Receive payouts to your PayPal email')</span>
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
                    <label class="app-field">
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
                </div>

                <div class="app-payout-panel" data-panel="bank" @if($selectedMethod !== 'bank') hidden @endif>
                    <div class="app-form-split">
                        <label class="app-field">
                            <span>@lang('Bank name')</span>
                            <input
                                type="text"
                                name="payout_bank_name"
                                class="form-control"
                                value="{{ old('payout_bank_name', $user->payout_bank_name) }}"
                                placeholder="@lang('e.g. Chase Bank')"
                            >
                        </label>

                        <label class="app-field">
                            <span>@lang('Account holder name')</span>
                            <input
                                type="text"
                                name="payout_account_name"
                                class="form-control"
                                value="{{ old('payout_account_name', $user->payout_account_name) }}"
                                placeholder="@lang('Name on the account')"
                            >
                        </label>
                    </div>

                    <div class="app-form-split">
                        <label class="app-field">
                            <span>@lang('Account number')</span>
                            <input
                                type="text"
                                name="payout_account_number"
                                class="form-control"
                                value="{{ old('payout_account_number', $user->payout_account_number) }}"
                                placeholder="@lang('Account number')"
                                inputmode="numeric"
                            >
                        </label>

                        <label class="app-field">
                            <span>@lang('Routing number')</span>
                            <input
                                type="text"
                                name="payout_routing_number"
                                class="form-control"
                                value="{{ old('payout_routing_number', $user->payout_routing_number) }}"
                                placeholder="@lang('9-digit routing number')"
                                inputmode="numeric"
                            >
                        </label>
                    </div>
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

@push('script')
<script>
    (function() {
        "use strict";

        const form = document.getElementById('payoutSetupForm');
        if (!form) return;

        const panels = form.querySelectorAll('.app-payout-panel');
        const methodInputs = form.querySelectorAll('input[name="payout_method"]');
        const paypalEmail = form.querySelector('[name="payout_paypal_email"]');
        const bankFields = [
            form.querySelector('[name="payout_bank_name"]'),
            form.querySelector('[name="payout_account_name"]'),
            form.querySelector('[name="payout_account_number"]'),
            form.querySelector('[name="payout_routing_number"]'),
        ];

        function syncPanels() {
            const selected = form.querySelector('input[name="payout_method"]:checked')?.value;

            panels.forEach(function(panel) {
                panel.hidden = panel.dataset.panel !== selected;
            });

            if (paypalEmail) {
                paypalEmail.required = selected === 'paypal';
            }

            bankFields.forEach(function(field) {
                if (field) {
                    field.required = selected === 'bank';
                }
            });
        }

        methodInputs.forEach(function(input) {
            input.addEventListener('change', syncPanels);
        });

        syncPanels();
    })();
</script>
@endpush
