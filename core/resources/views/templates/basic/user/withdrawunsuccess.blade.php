@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="app-page">
        <div class="app-container">
            <div class="app-topbar">
                <a class="app-icon-btn" href="{{ route('user.withdraw.index') }}" aria-label="@lang('Back to withdraw')">
                    <i class="las la-arrow-left"></i>
                </a>
                <a class="app-brand" href="{{ route('user.home') }}">
                    <img src="{{ siteLogo() }}" alt="To-App">
                    <span>@lang('Unsuccessful')</span>
                </a>
                <a class="app-icon-btn" href="{{ route('ticket.open') }}" aria-label="@lang('Support')">
                    <i class="las la-headset"></i>
                </a>
            </div>

            <section class="app-result-card is-danger">
                <span class="app-result-icon">
                    <i class="las la-exclamation-triangle"></i>
                </span>
                <h1>@lang('Withdrawal was not completed')</h1>
                <p>@lang('We encountered an issue with your withdrawal. Please check your balance, method limits, or contact support if this keeps happening.')</p>

                <div class="app-card mt-4 text-start" style="box-shadow:none; background: rgba(220, 38, 38, .06);">
                    <div class="d-flex align-items-start gap-3">
                        <span class="app-list-icon" style="color:#dc2626; background: rgba(220, 38, 38, .1);">
                            <i class="las la-info-circle"></i>
                        </span>
                        <div>
                            <h2 class="fs-6 fw-bold mb-1">@lang('What to check')</h2>
                            <p class="mb-0 text-muted">@lang('Make sure the requested amount is within the method limit and your available balance can cover any withdrawal charge.')</p>
                        </div>
                    </div>
                </div>

                <div class="app-hero-actions mt-4">
                    <a href="{{ route('user.withdraw.index') }}" class="app-btn app-btn-primary">
                        <i class="las la-redo-alt"></i>
                        @lang('Try again')
                    </a>
                    <a href="{{ route('ticket.open') }}" class="app-btn app-btn-secondary">
                        <i class="las la-headset"></i>
                        @lang('Contact support')
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
            background: linear-gradient(135deg, #dc2626, #f97316);
            box-shadow: 0 20px 46px rgba(220, 38, 38, .22);
            font-size: 44px;
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
            max-width: 500px;
            color: var(--to-muted);
        }
    </style>
@endpush
