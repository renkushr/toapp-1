@extends($activeTemplate . 'layouts.master')

@section('content')
@php
    $supportEmail = trim((string) (gs('support_email') ?: config('toapp.support_email', 'tonkowtek@to-app.com')));
@endphp
<div class="app-page">
    <div class="app-container">
        <div class="app-topbar">
            <a class="app-icon-btn" href="{{ route('user.home') }}" aria-label="@lang('Back')">
                <i class="las la-arrow-left"></i>
            </a>
            <a class="app-brand" href="{{ route('user.home') }}">
                <img src="{{ siteLogo() }}" alt="To-App">
                <span>@lang('Contact support')</span>
            </a>
            <span class="app-icon-btn" aria-hidden="true" style="visibility:hidden"><i class="las la-headset"></i></span>
        </div>

        <section class="app-hero">
            <span class="app-eyebrow"><i class="las la-headset"></i> @lang('Help & support')</span>
            <h1>@lang('Choose how you want to reach us.')</h1>
            <p>@lang('Chat with our team or send an email.')</p>
        </section>

        <div class="app-section-title">
            <div>
                <h2>@lang('Contact options')</h2>
                <p>@lang('Pick the channel that works best for you.')</p>
            </div>
        </div>

        <div class="app-list-card">
            <a class="app-list-item" href="{{ route('ticket.index') }}">
                <span class="app-list-icon"><i class="las la-comments"></i></span>
                <div class="app-list-body">
                    <strong>@lang('Chat')</strong>
                    <span>@lang('Message our support team and follow replies in one place')</span>
                </div>
                <i class="las la-angle-right"></i>
            </a>

            <a class="app-list-item" href="mailto:{{ $supportEmail }}">
                <span class="app-list-icon"><i class="las la-envelope"></i></span>
                <div class="app-list-body">
                    <strong>@lang('Email')</strong>
                    <span>{{ $supportEmail }}</span>
                </div>
                <i class="las la-angle-right"></i>
            </a>
        </div>

        <div class="app-section-title mt-4">
            <div>
                <h2>@lang('Follow us')</h2>
                <p>@lang('Stay connected on social media.')</p>
            </div>
        </div>
        <div class="app-list-card" style="padding: 16px;">
            @include('partials.site_contact_links', [
                'variant' => 'default',
                'showEmail' => false,
                'showLabel' => false,
            ])
        </div>
    </div>
</div>
@endsection
