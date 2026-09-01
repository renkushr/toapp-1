@extends($activeTemplate . 'layouts.master')

@section('content')
@php
    $payType = ($criteria['pay_type'] ?? 'year') === 'month' ? 'month' : 'year';
    $range = (int) ($criteria['price_range'] ?? 1);
    $coverageLength = (int) ($criteria['coverage_length'] ?? 0);
    $isTermLife = ($criteria['product_category'] ?? $plan->category) === 'Term Life';
@endphp
<div class="app-page">
    <div class="app-container">
        <div class="app-topbar">
            <a class="app-icon-btn" href="{{ route('user.plans.results') }}" aria-label="@lang('Back')">
                <i class="las la-arrow-left"></i>
            </a>
            <a class="app-brand" href="{{ route('user.home') }}">
                <img src="{{ siteLogo() }}" alt="To-app">
                <span>{{ $plan->name }}</span>
            </a>
            <span class="app-icon-btn" style="visibility:hidden"><i class="las la-info"></i></span>
        </div>

        <section class="app-hero">
            <span class="app-eyebrow"><i class="las la-shield-alt"></i> {{ $plan->category }}</span>
            <h1>{{ $plan->name }}</h1>
            <p>{{ $plan->descript ?: __('Review this product, then continue to agent chat.') }}</p>
            <div class="app-pills">
                <span class="app-pill">${{ number_format($plan->matched_price, 0) }} / {{ $payType === 'month' ? __('month') : __('year') }}</span>
                @if($isTermLife && $coverageLength)
                    <span class="app-pill">@lang('Length') {{ $coverageLength }} @lang('years')</span>
                @endif
            </div>
        </section>

        <div class="app-card">
            @if($plan->term)
                <div class="mb-3">{!! $plan->term !!}</div>
            @else
                <p class="text-muted mb-0">@lang('An agent will walk you through full policy details after you continue.')</p>
            @endif
        </div>

        <form method="POST" action="{{ route('user.plans.products.select', $plan) }}" class="mt-3">
            @csrf
            <input type="hidden" name="price_range" value="{{ $range }}">
            <button class="app-btn app-btn-primary w-100" type="submit">
                @lang('Get a quote') <i class="las la-file-invoice-dollar"></i>
            </button>
        </form>
    </div>
</div>
@endsection
