@extends($activeTemplate . 'layouts.master')

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@section('content')
    <div class="app-page app-withdraw-preview">
        <div class="app-container">
            <div class="app-topbar">
                <a class="app-icon-btn" href="{{ route('user.withdraw.index') }}" aria-label="@lang('Back to withdraw')">
                    <i class="las la-arrow-left"></i>
                </a>
                <a class="app-brand" href="{{ route('user.home') }}">
                    <img src="{{ siteLogo() }}" alt="To-App">
                    <span>@lang('Review')</span>
                </a>
                <a class="app-icon-btn" href="{{ route('user.withdraw.history') }}" aria-label="@lang('Withdrawal history')">
                    <i class="las la-history"></i>
                </a>
            </div>

            <section class="app-hero">
                <span class="app-eyebrow">
                    <i class="las la-clipboard-check"></i>
                    @lang('Final details')
                </span>
                <h1>@lang('Confirm your withdrawal.')</h1>
                <p>@lang('Review the amount, charge, and final payout. Add any required destination information before sending the request.')</p>

                <div class="app-pills">
                    <span class="app-pill">
                        <i class="las la-wallet"></i>
                        @lang('Balance') {{ showAmount(auth()->user()->balance) }}
                    </span>
                    <span class="app-pill">
                        <i class="las la-university"></i>
                        {{ __($withdraw->method->name) }}
                    </span>
                </div>
            </section>

            <form action="{{ route('user.withdraw.submit') }}" class="disableSubmission" method="post" enctype="multipart/form-data">
                @csrf

                <div class="row app-grid mt-3">
                    <div class="col-lg-5">
                        <div class="app-section-title">
                            <div>
                                <h2>@lang('Summary')</h2>
                                <p>@lang('This is what will be submitted for processing.')</p>
                            </div>
                        </div>

                        <div class="app-card">
                            <div class="withdraw-preview-amount">
                                <span>@lang('You request')</span>
                                <strong>{{ showAmount($withdraw->amount) }}</strong>
                            </div>

                            <div class="withdraw-summary-box mt-3">
                                <div>
                                    <span>@lang('Charge')</span>
                                    <strong>{{ showAmount($withdraw->charge) }}</strong>
                                </div>
                                <div>
                                    <span>@lang('After charge')</span>
                                    <strong>{{ showAmount($withdraw->after_charge) }}</strong>
                                </div>
                                <div>
                                    <span>@lang('You will receive')</span>
                                    <strong>{{ showAmount($withdraw->final_amount, currencyFormat: false) }} {{ __($withdraw->currency) }}</strong>
                                </div>
                                <div>
                                    <span>@lang('Conversion rate')</span>
                                    <strong>1 {{ __(gs('cur_text')) }} = {{ showAmount($withdraw->rate, currencyFormat: false) }} {{ __($withdraw->currency) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="app-section-title">
                            <div>
                                <h2>@lang('Destination details')</h2>
                                <p>@lang('Fill the requested payout details carefully.')</p>
                            </div>
                        </div>

                        <div class="app-card">
                            @if ($withdraw->method->form_id)
                                <x-viser-form identifier="id" identifierValue="{{ $withdraw->method->form_id }}" />
                            @elseif(auth()->user()->hasPayoutSetup() && auth()->user()->payoutMethodMatches($withdraw->method->name))
                                <div class="withdraw-summary-box">
                                    @foreach(auth()->user()->payoutInformationPayload() as $field)
                                        <div>
                                            <span>{{ __($field['name']) }}</span>
                                            <strong>{{ $field['value'] }}</strong>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-muted mt-3 mb-0">@lang('These details were saved in your payout settings.')</p>
                            @else
                                <div class="app-empty-state">
                                    <i class="las la-exclamation-circle d-block fs-1 mb-2"></i>
                                    <p class="mb-3">@lang('Please save your payout details before continuing.')</p>
                                    <a href="{{ route('user.bank.setting') }}" class="app-btn app-btn-primary">
                                        <i class="las la-university"></i>
                                        @lang('Set payout details')
                                    </a>
                                </div>
                            @endif

                            @if (auth()->user()->ts)
                                <div class="form-group mt-3">
                                    <label class="fw-bold mb-2">@lang('Authenticator Code')</label>
                                    <input type="text" name="authenticator_code" class="form-control" required>
                                </div>
                            @endif

                            <div class="app-card mt-3" style="background: rgba(18, 104, 243, .06); box-shadow: none;">
                                <div class="d-flex align-items-start gap-3">
                                    <span class="app-list-icon"><i class="las la-shield-alt"></i></span>
                                    <div>
                                        <h3 class="fs-6 fw-bold mb-1">@lang('Processing note')</h3>
                                        <p class="mb-0 text-muted">@lang('The withdrawal will be marked pending after confirmation and reviewed by the admin team.')</p>
                                    </div>
                                </div>
                            </div>

                            @php
                                $canSubmitWithdraw = $withdraw->method->form_id
                                    || (auth()->user()->hasPayoutSetup() && auth()->user()->payoutMethodMatches($withdraw->method->name));
                            @endphp

                            @if ($canSubmitWithdraw)
                            <button type="submit" class="app-btn app-btn-primary w-100 mt-3">
                                <i class="las la-check"></i>
                                @lang('Submit withdrawal request')
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .withdraw-preview-amount {
            padding: 22px;
            border-radius: 24px;
            color: #fff;
            background: linear-gradient(135deg, #053d79, #1268f3 58%, #13c8d6);
        }

        .withdraw-preview-amount span {
            display: block;
            color: rgba(255, 255, 255, .78);
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .withdraw-preview-amount strong {
            display: block;
            margin-top: 8px;
            color: #fff;
            font-size: clamp(34px, 7vw, 48px);
            font-weight: 900;
            line-height: 1;
            letter-spacing: -.05em;
        }
    </style>
@endpush
