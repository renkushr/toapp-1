@extends($activeTemplate . 'layouts.master')

@section('content')
<div class="app-page">
    <div class="app-container">
        <div class="app-topbar">
            <a class="app-icon-btn" href="{{ $application->plan ? route('user.plans.products.show', $application->plan) : route('user.plans.results') }}" aria-label="@lang('Back')">
                <i class="las la-arrow-left"></i>
            </a>
            <a class="app-brand" href="{{ route('user.home') }}">
                <img src="{{ siteLogo() }}" alt="To-app">
                <span>@lang('Terms')</span>
            </a>
            <span class="app-icon-btn" style="visibility:hidden"><i class="las la-file-contract"></i></span>
        </div>

        <section class="app-hero">
            <span class="app-eyebrow"><i class="las la-file-contract"></i> @lang('Almost done')</span>
            <h1>@lang('Accept terms to chat with your agent')</h1>
            <p>@lang('Selected product'): <strong>{{ optional($application->plan)->name }}</strong></p>
        </section>

        <div class="app-card">
            @if(optional($application->plan)->term)
                <div class="mb-3" style="max-height:280px;overflow:auto;">{!! $application->plan->term !!}</div>
            @else
                <p class="text-muted">@lang('By continuing, you agree to proceed with agent-assisted enrollment. Payment is completed outside this system after your agent confirms.')</p>
            @endif

            <form method="POST" action="{{ route('user.plans.terms.confirm', $application) }}">
                @csrf
                <label class="d-flex align-items-start gap-2 mb-3">
                    <input type="checkbox" name="accept" value="1" required>
                    <span>@lang('I accept the terms and want to continue to my agent.')</span>
                </label>
                @error('accept')<div class="text-danger mb-2">{{ $message }}</div>@enderror
                <button class="app-btn app-btn-primary w-100" type="submit">
                    @lang('Confirm & continue') <i class="las la-comments"></i>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
