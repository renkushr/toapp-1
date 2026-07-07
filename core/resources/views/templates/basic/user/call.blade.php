@extends($activeTemplate . 'layouts.master')

@section('content')
<div class="app-page">
    <div class="app-container">
        <div class="app-topbar">
            <a class="app-icon-btn" href="{{ route('user.setting') }}" aria-label="@lang('Back to settings')">
                <i class="las la-arrow-left"></i>
            </a>
            <a class="app-brand" href="{{ route('user.home') }}">
                <img src="{{ siteLogo() }}" alt="To-app">
                <span>@lang('Support')</span>
            </a>
            <a class="app-icon-btn" href="{{ route('ticket.open') }}" aria-label="@lang('New ticket')">
                <i class="las la-plus"></i>
            </a>
        </div>

        <section class="app-hero">
            <span class="app-eyebrow"><i class="las la-headset"></i> @lang('Help & support')</span>
            <h1>@lang('Reach our team when you need a human.')</h1>
            <p>@lang('Open a ticket for tracked follow-up, call us for urgent issues, or email if you prefer written contact.')</p>
        </section>

        <div class="app-section-title">
            <div>
                <h2>@lang('Contact options')</h2>
                <p>@lang('Choose the channel that fits your request best.')</p>
            </div>
        </div>

        <div class="app-list-card">
            <a class="app-list-item" href="{{ route('ticket.index') }}">
                <span class="app-list-icon"><i class="las la-ticket-alt"></i></span>
                <div class="app-list-body">
                    <strong>@lang('My support tickets')</strong>
                    <span>@lang('Review open requests and previous replies')</span>
                </div>
                <i class="las la-angle-right"></i>
            </a>

            <a class="app-list-item" href="{{ route('ticket.open') }}">
                <span class="app-list-icon"><i class="las la-edit"></i></span>
                <div class="app-list-body">
                    <strong>@lang('Open new ticket')</strong>
                    <span>@lang('Describe your issue and attach files if needed')</span>
                </div>
                <i class="las la-angle-right"></i>
            </a>

            <a class="app-list-item" href="tel:+1-725-2947734">
                <span class="app-list-icon"><i class="las la-phone"></i></span>
                <div class="app-list-body">
                    <strong>@lang('Call us')</strong>
                    <span>+1 (725) 294-7734</span>
                </div>
                <i class="las la-angle-right"></i>
            </a>

            <a class="app-list-item" href="mailto:support@to-app.com">
                <span class="app-list-icon"><i class="las la-envelope"></i></span>
                <div class="app-list-body">
                    <strong>@lang('Send email')</strong>
                    <span>support@to-app.com</span>
                </div>
                <i class="las la-angle-right"></i>
            </a>
        </div>
    </div>
</div>
@endsection
