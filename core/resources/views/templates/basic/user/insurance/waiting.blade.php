@extends($activeTemplate . 'layouts.master')

@section('content')
<div class="app-page">
    <div class="app-container">
        <div class="app-topbar">
            <a class="app-icon-btn" href="{{ route('user.plans.terms', $application) }}" aria-label="@lang('Back')">
                <i class="las la-arrow-left"></i>
            </a>
            <a class="app-brand" href="{{ route('user.home') }}">
                <img src="{{ siteLogo() }}" alt="To-App">
                <span>@lang('Waiting')</span>
            </a>
            <span class="app-icon-btn" style="visibility:hidden"><i class="las la-user-clock"></i></span>
        </div>

        <section class="app-hero">
            <span class="app-eyebrow"><i class="las la-user-clock"></i> @lang('Agent assignment')</span>
            <h1>@lang('We are finding an agent for your ZIP code')</h1>
            <p>@lang('Your application is saved. An admin will assign an agent who covers') {{ $user->zipcode }}.</p>
        </section>

        <div class="app-card">
            <div class="withdraw-summary-box">
                <div><span>@lang('Reference')</span><strong>{{ $application->reference }}</strong></div>
                <div><span>@lang('Product')</span><strong>{{ optional($application->plan)->name ?: '—' }}</strong></div>
                <div><span>@lang('ZIP code')</span><strong>{{ $user->zipcode }}</strong></div>
            </div>
            <a class="app-btn app-btn-secondary w-100 mt-3" href="{{ route('user.plans.conditions.edit', $application) }}">
                <i class="las la-sliders-h"></i> @lang('Edit plan conditions')
            </a>
            <a class="app-btn app-btn-secondary w-100 mt-2" href="{{ route('user.home') }}">@lang('Back to dashboard')</a>
        </div>
    </div>
</div>
@endsection
