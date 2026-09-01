@extends($activeTemplate . 'layouts.master')

@section('content')
<div class="app-page">
    <div class="app-container">
        <div class="app-topbar">
            <a class="app-icon-btn" href="{{ route('user.plans.survey') }}" aria-label="@lang('Back')">
                <i class="las la-arrow-left"></i>
            </a>
            <a class="app-brand" href="{{ route('user.home') }}">
                <img src="{{ siteLogo() }}" alt="To-app">
                <span>@lang('Products')</span>
            </a>
            <span class="app-icon-btn" style="visibility:hidden"><i class="las la-shield-alt"></i></span>
        </div>

        <section class="app-hero">
            <span class="app-eyebrow"><i class="las la-shield-alt"></i> @lang('Step 3 of 3')</span>
            <h1>@lang('Your matching products')</h1>
            <p>@lang('Select a product, then we connect you to your agent chat immediately.')</p>
        </section>

        @include($activeTemplate . 'user.insurance.partials.steps', ['activeStep' => 3])

        <div class="row app-grid">
            @forelse($plans as $plan)
                <div class="col-md-6 col-xl-4">
                    <article class="app-plan-card">
                        <span class="app-plan-badge"><i class="las la-shield-alt"></i> {{ $plan->category }}</span>
                        <h3>{{ $plan->name }}</h3>
                        <div class="app-plan-return">
                            <strong>${{ number_format($plan->matched_price, 0) }}</strong>
                            <span>@lang('per') {{ ($criteria['pay_type'] ?? 'year') === 'month' ? __('month') : __('year') }}</span>
                        </div>
                        <p class="app-plan-range">{{ $plan->descript ?: __('Coverage matched to your profile') }}</p>
                        <a class="app-btn app-btn-primary" href="{{ route('user.plans.products.show', $plan) }}">
                            @lang('View & select')
                        </a>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="app-empty-state">
                        <i class="las la-search d-block fs-1 mb-2"></i>
                        @lang('No products match your criteria right now.')
                        <div class="mt-3">
                            <a class="app-btn app-btn-secondary" href="{{ route('user.plans.survey') }}">@lang('Adjust survey')</a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
