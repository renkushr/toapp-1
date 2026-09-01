@extends($activeTemplate . 'layouts.master')

@section('content')
@php
    $supportPhone = trim((string) (gs('support_phone') ?: ''));
    $supportEmail = trim((string) (gs('support_email') ?: ''));
    $phoneHref = $supportPhone !== '' ? preg_replace('/[^\d+]/', '', $supportPhone) : '';
@endphp
<div class="app-page">
    <div class="app-container">
        <div class="app-topbar">
            <a class="app-icon-btn" href="{{ route('user.home') }}" aria-label="@lang('Back')">
                <i class="las la-arrow-left"></i>
            </a>
            <a class="app-brand" href="{{ route('user.home') }}">
                <img src="{{ siteLogo() }}" alt="To-app">
                <span>@lang('Contact support')</span>
            </a>
            <span class="app-icon-btn" aria-hidden="true" style="visibility:hidden"><i class="las la-headset"></i></span>
        </div>

        <section class="app-hero">
            <span class="app-eyebrow"><i class="las la-headset"></i> @lang('Help & support')</span>
            <h1>@lang('Choose how you want to reach us.')</h1>
            <p>@lang('Chat with our team, call for urgent help, or send an email.')</p>
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

            @if($supportPhone !== '')
                <a class="app-list-item" href="tel:{{ $phoneHref }}">
                    <span class="app-list-icon"><i class="las la-phone"></i></span>
                    <div class="app-list-body">
                        <strong>@lang('Call')</strong>
                        <span>{{ $supportPhone }}</span>
                    </div>
                    <i class="las la-angle-right"></i>
                </a>
            @else
                <div class="app-list-item" style="opacity:.65; cursor:default;">
                    <span class="app-list-icon"><i class="las la-phone"></i></span>
                    <div class="app-list-body">
                        <strong>@lang('Call')</strong>
                        <span>@lang('Phone number is not set yet. Please check back later.')</span>
                    </div>
                </div>
            @endif

            @if($supportEmail !== '')
                <a class="app-list-item" href="mailto:{{ $supportEmail }}">
                    <span class="app-list-icon"><i class="las la-envelope"></i></span>
                    <div class="app-list-body">
                        <strong>@lang('Email')</strong>
                        <span>{{ $supportEmail }}</span>
                    </div>
                    <i class="las la-angle-right"></i>
                </a>
            @else
                <div class="app-list-item" style="opacity:.65; cursor:default;">
                    <span class="app-list-icon"><i class="las la-envelope"></i></span>
                    <div class="app-list-body">
                        <strong>@lang('Email')</strong>
                        <span>@lang('Email address is not set yet. Please check back later.')</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
