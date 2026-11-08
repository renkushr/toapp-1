@extends($activeTemplate . 'layouts.master')

@section('content')
    @php
        $summary = session('withdraw_summary');
    @endphp

    <div class="app-page">
        <div class="app-container">
            <div class="app-topbar">
                <a class="app-icon-btn" href="{{ route('user.withdraw.history') }}" aria-label="@lang('Withdrawal history')">
                    <i class="las la-arrow-left"></i>
                </a>
                <a class="app-brand" href="{{ route('user.home') }}">
                    <img src="{{ siteLogo() }}" alt="To-App">
                    <span>@lang('Success')</span>
                </a>
                <a class="app-icon-btn" href="{{ route('user.home') }}" aria-label="@lang('Dashboard')">
                    <i class="las la-home"></i>
                </a>
            </div>

            <section class="app-result-card is-success">
                <span class="app-result-icon">
                    <i class="las la-check"></i>
                </span>
                <h1>@lang('Withdrawal request sent')</h1>
                <p>@lang('Your request is now pending review. You can track its progress from withdrawal history.')</p>

                <div class="withdraw-summary-box mt-4">
                    <div>
                        <span>@lang('Method')</span>
                        <strong>{{ $summary['method_name'] ?? __('Withdrawal request') }}</strong>
                    </div>
                    <div>
                        <span>@lang('Destination')</span>
                        <strong>{{ $summary['summary_value'] ?? __('Details submitted') }}</strong>
                    </div>
                    <div>
                        <span>@lang('Total withdraw')</span>
                        <strong>
                            {{ isset($summary['final_amount']) ? showAmount($summary['final_amount'], currencyFormat: false) . ' ' . $summary['currency'] : '--' }}
                        </strong>
                    </div>
                </div>

                <div class="app-hero-actions mt-4">
                    <a href="{{ route('user.withdraw.history') }}" class="app-btn app-btn-primary">
                        <i class="las la-history"></i>
                        @lang('View withdrawal history')
                    </a>
                    <a href="{{ route('user.home') }}" class="app-btn app-btn-secondary">
                        <i class="las la-home"></i>
                        @lang('Back to dashboard')
                    </a>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .app-result-card {
            width: min(100%, 680px);
            margin: 28px auto 0;
            padding: clamp(28px, 6vw, 48px);
            border: 1px solid rgba(255, 255, 255, .78);
            border-radius: 34px;
            background: rgba(255, 255, 255, .92);
            box-shadow: var(--to-shadow);
            text-align: center;
        }

        .app-result-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 92px;
            height: 92px;
            border-radius: 30px;
            color: #fff;
            background: linear-gradient(135deg, #16a34a, #13c8d6);
            box-shadow: 0 20px 46px rgba(22, 163, 74, .24);
            font-size: 48px;
        }

        .app-result-card h1 {
            margin: 22px 0 10px;
            color: var(--to-ink);
            font-size: clamp(30px, 7vw, 46px);
            font-weight: 900;
            letter-spacing: -.05em;
        }

        .app-result-card p {
            margin: 0 auto;
            max-width: 480px;
            color: var(--to-muted);
        }
    </style>
@endpush
