@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="app-page app-withdraw">
        <div class="app-container">
            <div class="app-topbar">
                <a class="app-icon-btn" href="{{ route('user.home') }}" aria-label="@lang('Back to dashboard')">
                    <i class="las la-arrow-left"></i>
                </a>
                <a class="app-brand" href="{{ route('user.home') }}">
                    <img src="{{ siteLogo() }}" alt="To-app">
                    <span>@lang('Withdraw')</span>
                </a>
                <a class="app-icon-btn" href="{{ route('user.withdraw.history') }}" aria-label="@lang('Withdrawal history')">
                    <i class="las la-history"></i>
                </a>
            </div>

            <section class="app-hero">
                <span class="app-eyebrow">
                    <i class="las la-wallet"></i>
                    @lang('Withdraw money')
                </span>
                <h1>@lang('Move funds out safely.')</h1>
                <p>@lang('Enter an amount, choose a payout method, then review fees and destination details before the final confirmation.')</p>

                <div class="app-pills">
                    <span class="app-pill">
                        <i class="las la-check-circle"></i>
                        @lang('Ready to withdraw') {{ showAmount($withdrawSummary['withdrawable_amount']) }}
                    </span>
                    <span class="app-pill">
                        <i class="las la-hourglass-half"></i>
                        @lang('Pending') {{ showAmount($withdrawSummary['pending_amount']) }}
                    </span>
                </div>
            </section>

            @if(!$withdrawSummary['has_active_plan'])
                <div class="app-card mt-3" style="border-color: rgba(249, 115, 22, .35); background: rgba(249, 115, 22, .08);">
                    <div class="d-flex align-items-start gap-3">
                        <span class="app-list-icon"><i class="las la-exclamation-triangle"></i></span>
                        <div>
                            <h3 class="fs-6 fw-bold mb-1">@lang('Active plan required')</h3>
                            <p class="mb-0 text-muted">@lang('You need at least one running plan before you can withdraw. Pending earnings also unlock after :days days.', ['days' => $withdrawSummary['holding_days']])</p>
                            <a class="app-link d-inline-flex mt-2" href="{{ route('user.investment.log') }}">@lang('View investments') <i class="las la-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            @endif

            <div class="withdraw-summary-box mt-3">
                <div>
                    <span>@lang('Total withdraw balance')</span>
                    <strong>{{ showAmount($withdrawSummary['total_amount']) }}</strong>
                </div>
                <div>
                    <span>@lang('Ready (> :days days)', ['days' => $withdrawSummary['holding_days']])</span>
                    <strong>{{ showAmount($withdrawSummary['ready_amount']) }}</strong>
                </div>
                <div>
                    <span>@lang('Pending (< :days days)', ['days' => $withdrawSummary['holding_days']])</span>
                    <strong>{{ showAmount($withdrawSummary['pending_amount']) }}</strong>
                </div>
                <div>
                    <span>@lang('Wallet balance')</span>
                    <strong>{{ showAmount($withdrawSummary['wallet_balance']) }}</strong>
                </div>
                @if($withdrawSummary['next_unlock_at'])
                    <div>
                        <span>@lang('Next unlock')</span>
                        <strong>{{ showDateTime($withdrawSummary['next_unlock_at'], 'M d, Y') }}</strong>
                    </div>
                @endif
            </div>

            <form action="{{ route('user.withdraw.money') }}" method="post" class="withdraw-form disableSubmission" @unless($withdrawSummary['has_active_plan'] && $withdrawSummary['withdrawable_amount'] > 0) style="opacity:.72" @endunless>
                @csrf

                <div class="row app-grid mt-3">
                    <div class="col-lg-5">
                        <div class="app-section-title">
                            <div>
                                <h2>@lang('Amount')</h2>
                                <p>@lang('Start with the amount you want to request.')</p>
                            </div>
                        </div>

                        <div class="app-card">
                            <label class="fw-bold mb-2" for="withdrawAmount">@lang('Withdraw amount')</label>
                            <div class="withdraw-amount-box">
                                <span>{{ gs('cur_sym') }}</span>
                                <input id="withdrawAmount" name="amount" type="number" step="any" min="0" max="{{ $withdrawSummary['withdrawable_amount'] }}" placeholder="0.00" value="{{ old('amount') }}" autocomplete="off" required @disabled(!$withdrawSummary['has_active_plan'] || $withdrawSummary['withdrawable_amount'] <= 0)>
                            </div>

                            <div class="app-pills">
                                <span class="app-pill">
                                    <i class="las la-wallet"></i>
                                    @lang('You can withdraw') {{ showAmount($withdrawSummary['withdrawable_amount']) }}
                                </span>
                                <span class="app-pill" id="withdrawEstimatePill">
                                    <i class="las la-calculator"></i>
                                    @lang('Choose a method for estimate')
                                </span>
                            </div>

                            <div class="withdraw-summary-box mt-3">
                                <div>
                                    <span>@lang('Method charge')</span>
                                    <strong id="withdrawChargeText">--</strong>
                                </div>
                                <div>
                                    <span>@lang('Estimated receive')</span>
                                    <strong id="withdrawReceiveText">--</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="app-section-title">
                            <div>
                                <h2>@lang('Withdraw to')</h2>
                                <p>@lang('Select where this withdrawal should go.')</p>
                            </div>
                        </div>

                        <div class="row app-grid">
                            @forelse ($withdrawMethod as $method)
                                <div class="col-md-6">
                                    <label class="withdraw-method-card">
                                        <input type="radio" name="method_code" value="{{ $method->id }}" data-name="{{ __($method->name) }}"
                                            data-min="{{ showAmount($method->min_limit, currencyFormat: false) }}"
                                            data-max="{{ showAmount($method->max_limit, currencyFormat: false) }}"
                                            data-fixed-charge="{{ getAmount($method->fixed_charge) }}"
                                            data-percent-charge="{{ getAmount($method->percent_charge) }}"
                                            data-rate="{{ getAmount($method->rate) }}"
                                            data-currency="{{ __($method->currency) }}"
                                            @checked(old('method_code') == $method->id || $loop->first) required>
                                        <span class="withdraw-method-check"><i class="las la-check"></i></span>

                                        <span class="withdraw-method-icon">
                                            <i class="las la-university"></i>
                                        </span>
                                        <strong>{{ __($method->name) }}</strong>
                                        <span>@lang('Limit') {{ showAmount($method->min_limit) }} - {{ showAmount($method->max_limit) }}</span>
                                        <small>
                                            @lang('Charge') {{ showAmount($method->fixed_charge) }}
                                            + {{ showAmount($method->percent_charge, 2, false, false, false) }}%
                                        </small>
                                    </label>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="app-empty-state">
                                        <i class="las la-university d-block fs-1 mb-2"></i>
                                        @lang('No withdrawal methods are active.')
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <div class="app-card mt-3">
                            <div class="d-flex align-items-start gap-3">
                                <span class="app-list-icon"><i class="las la-info-circle"></i></span>
                                <div>
                                    <h3 class="fs-6 fw-bold mb-1">@lang('What happens next?')</h3>
                                    <p class="mb-0 text-muted">@lang('After you confirm, your request is reviewed by admin. Payout is completed outside this system after approval.')</p>
                                </div>
                            </div>
                        </div>

                        @if ($withdrawMethod->count() && $withdrawSummary['has_active_plan'] && $withdrawSummary['withdrawable_amount'] > 0)
                            <button class="app-btn app-btn-primary w-100 mt-3" type="submit">
                                <i class="las la-arrow-right"></i>
                                @lang('Continue to review')
                            </button>
                        @elseif(!$withdrawSummary['has_active_plan'])
                            <a class="app-btn app-btn-primary w-100 mt-3" href="{{ route('user.investment.log') }}">
                                <i class="las la-layer-group"></i>
                                @lang('View investments')
                            </a>
                        @else
                            <a class="app-btn app-btn-secondary w-100 mt-3" href="{{ route('user.call') }}">
                                <i class="las la-headset"></i>
                                @lang('Contact support')
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .withdraw-amount-box {
            display: grid;
            grid-template-columns: auto 1fr;
            align-items: center;
            gap: 12px;
            min-height: 86px;
            padding: 0 18px;
            border: 1px solid rgba(18, 104, 243, .16);
            border-radius: 24px;
            background: linear-gradient(180deg, rgba(255, 255, 255, .98), rgba(239, 248, 255, .9));
        }

        .withdraw-amount-box span {
            color: var(--to-blue);
            font-size: 30px;
            font-weight: 900;
        }

        .withdraw-amount-box input {
            width: 100%;
            border: 0;
            outline: 0;
            color: var(--to-ink);
            background: transparent;
            font-size: clamp(34px, 8vw, 54px);
            font-weight: 900;
            letter-spacing: -.06em;
            text-align: right;
        }

        .withdraw-amount-box input::placeholder {
            color: rgba(100, 116, 139, .34);
        }

        .withdraw-summary-box {
            display: grid;
            gap: 10px;
        }

        .withdraw-summary-box > div {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 16px;
            background: rgba(18, 104, 243, .06);
        }

        .withdraw-summary-box span {
            color: var(--to-muted);
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .withdraw-summary-box strong {
            color: var(--to-ink);
            font-weight: 900;
        }

        .withdraw-method-card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-height: 190px;
            gap: 8px;
            padding: 18px;
            border: 1px solid rgba(255, 255, 255, .78);
            border-radius: 26px;
            background: rgba(255, 255, 255, .92);
            box-shadow: 0 18px 42px rgba(15, 23, 42, .08);
            cursor: pointer;
            transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
        }

        .withdraw-method-card:hover {
            transform: translateY(-2px);
        }

        .withdraw-method-card input {
            position: absolute;
            inset: 0;
            opacity: 0;
            pointer-events: none;
        }

        .withdraw-method-card:has(input:checked) {
            border-color: rgba(18, 104, 243, .55);
            box-shadow: 0 24px 54px rgba(18, 104, 243, .18);
        }

        .withdraw-method-icon,
        .withdraw-method-check {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
        }

        .withdraw-method-icon {
            width: 46px;
            height: 46px;
            color: var(--to-blue);
            background: rgba(18, 104, 243, .1);
            font-size: 24px;
        }

        .withdraw-method-check {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 30px;
            height: 30px;
            color: #fff;
            background: rgba(100, 116, 139, .22);
            opacity: .55;
        }

        .withdraw-method-card:has(input:checked) .withdraw-method-check {
            background: linear-gradient(135deg, #1268f3, #13c8d6);
            opacity: 1;
        }

        .withdraw-method-card strong {
            color: var(--to-ink);
            font-size: 18px;
            font-weight: 900;
            letter-spacing: -.03em;
        }

        .withdraw-method-card span:not(.withdraw-method-icon):not(.withdraw-method-check) {
            color: var(--to-muted);
            font-size: 13px;
            font-weight: 700;
        }

        .withdraw-method-card small {
            margin-top: auto;
            color: var(--to-blue);
            font-weight: 800;
        }
    </style>
@endpush

@push('script')
    <script>
        (function() {
            "use strict";

            const amountInput = document.getElementById('withdrawAmount');
            const methodInputs = document.querySelectorAll('input[name="method_code"]');
            const chargeText = document.getElementById('withdrawChargeText');
            const receiveText = document.getElementById('withdrawReceiveText');
            const estimatePill = document.getElementById('withdrawEstimatePill');
            const currencySymbol = @json(gs('cur_sym'));

            function money(value, currency = '') {
                const amount = Number(value || 0);
                const formatted = `${currencySymbol}${amount.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                })}`;

                return currency ? `${formatted} ${currency}` : formatted;
            }

            function updateEstimate() {
                const checked = document.querySelector('input[name="method_code"]:checked');
                const amount = Number(amountInput?.value || 0);

                if (!checked) {
                    chargeText.textContent = '--';
                    receiveText.textContent = '--';
                    return;
                }

                const fixedCharge = Number(checked.dataset.fixedCharge || 0);
                const percentCharge = Number(checked.dataset.percentCharge || 0);
                const rate = Number(checked.dataset.rate || 1);
                const currency = checked.dataset.currency || '';
                const charge = fixedCharge + (amount * percentCharge / 100);
                const receive = Math.max(amount - charge, 0) * rate;

                chargeText.textContent = amount > 0 ? money(charge) : '--';
                receiveText.textContent = amount > 0 ? money(receive, currency) : '--';

                if (estimatePill) {
                    estimatePill.innerHTML = `<i class="las la-compress-arrows-alt"></i> ${checked.dataset.name}: ${checked.dataset.min} - ${checked.dataset.max}`;
                }
            }

            amountInput?.addEventListener('input', updateEstimate);
            methodInputs.forEach(function(input) {
                input.addEventListener('change', updateEstimate);
            });
            updateEstimate();
        })();
    </script>
@endpush
