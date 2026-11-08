@extends($activeTemplate . 'layouts.master')

@section('content')
    @php
        $planCollection = method_exists($plans, 'getCollection') ? $plans->getCollection() : collect($plans);
        $firstPlan = $planCollection->sortBy('min_amount')->first();
    @endphp

    <div class="app-page app-plans">
        <div class="app-container">
            <div class="app-topbar">
                <a class="app-icon-btn" href="{{ route('user.home') }}" aria-label="@lang('Back to dashboard')">
                    <i class="las la-arrow-left"></i>
                </a>
                <a class="app-brand" href="{{ route('user.home') }}">
                    <img src="{{ siteLogo() }}" alt="To-App">
                    <span>@lang('Plans')</span>
                </a>
                <a class="app-icon-btn" href="{{ route('user.investment.log') }}" aria-label="@lang('Investment log')">
                    <i class="las la-history"></i>
                </a>
            </div>

            <section class="app-hero">
                <span class="app-eyebrow">
                    <i class="las la-layer-group"></i>
                    @lang('Investment plans')
                </span>
                <h1>@lang('Choose a plan that fits your needs.')</h1>
                <p>@lang('Compare returns, ranges, and payout cycles before confirming. We made this page feel more guided so users understand what they are choosing.')</p>

                <div class="app-pills">
                    <span class="app-pill">
                        <i class="las la-list"></i>
                        {{ $planCollection->count() }} @lang('available')
                    </span>
                    @if ($firstPlan)
                        <span class="app-pill">
                            <i class="las la-seedling"></i>
                            @lang('Starts at') {{ showAmount($firstPlan->min_amount) }}
                        </span>
                    @endif
                    <span class="app-pill">
                        <i class="las la-wallet"></i>
                        @lang('Balance') {{ showAmount(auth()->user()->balance) }}
                    </span>
                </div>
            </section>

            <div class="app-section-title">
                <div>
                    <h2>@lang('Available plans')</h2>
                    <p>@lang('Tap a card to open the confirmation popup and enter your investment amount.')</p>
                </div>
                <a class="app-link" href="{{ route('user.investment.log') }}">
                    @lang('My investments')
                    <i class="las la-arrow-right"></i>
                </a>
            </div>

            <div class="row app-grid">
                @forelse($plans as $plan)
                    <div class="col-md-6 col-xl-4">
                        <article class="app-plan-card {{ $loop->first ? 'featured' : '' }}">
                            <span class="app-plan-badge">
                                <i class="las {{ $loop->first ? 'la-award' : 'la-shield-alt' }}"></i>
                                {{ $loop->first ? __('Popular start') : __('Plan option') }}
                            </span>

                            <h3>{{ __($plan->name) }}</h3>
                            <div class="app-plan-range">
                                {{ gs('cur_sym') }}{{ showAmount($plan->min_amount, currencyFormat: false) }}
                                -
                                {{ gs('cur_sym') }}{{ showAmount($plan->max_amount, currencyFormat: false) }}
                            </div>

                            <div class="app-plan-return">
                                <strong>
                                    {{ showAmount($plan->interest, 0, currencyFormat: false) }}{{ $plan->interest_type == Status::FIXED ? '' : '%' }}
                                </strong>
                                <span>
                                    {{ $plan->interest_type == Status::FIXED ? gs('cur_text') : __('return') }}
                                </span>
                            </div>

                            <ul class="app-feature-list">
                                <li>
                                    <i class="las la-check-circle"></i>
                                    @lang('Return every day')
                                </li>
                                <li>
                                    <i class="las la-check-circle"></i>
                                    @lang('Payout for') {{ $plan->total_return }} @lang('times')
                                </li>
                                <li>
                                    <i class="las la-check-circle"></i>
                                    @lang('Amount range is enforced before confirmation')
                                </li>
                            </ul>

                            <button type="button" data-name="{{ __($plan->name) }}" data-id="{{ $plan->id }}" data-min="{{ showAmount($plan->min_amount, currencyFormat: false) }}"
                                data-max="{{ showAmount($plan->max_amount, currencyFormat: false) }}" class="app-btn planModal" data-bs-toggle="modal" data-bs-target="#planModal">
                                <i class="las la-bolt"></i>
                                @lang('Invest now')
                            </button>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="app-empty-state">
                            <i class="las la-box-open d-block fs-1 mb-2"></i>
                            @lang('Plan does not found')
                        </div>
                    </div>
                @endforelse

                @if ($plans->hasPages())
                    <div class="col-12">
                        {{ paginateLinks($plans) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-plan-modal />
@endsection
