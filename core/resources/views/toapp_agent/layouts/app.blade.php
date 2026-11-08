<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ isset($pageTitle) ? __($pageTitle) : __('Agent') }} - TOAPP</title>
    <link rel="shortcut icon" type="image/png" href="{{ siteFavicon() }}">
    @include('partials.pwa_head', [
        'pwaName' => 'TOAPP Agent',
        'pwaManifest' => asset('manifest-agent.webmanifest'),
        'pwaTheme' => '#206bc4',
        'pwaBackground' => '#f1f5f9',
    ])
    @include('partials.tabler_assets')
    @stack('styles')
</head>
<body class="has-portal-footer">
    @php
        $currentAgent = auth('agent')->user();
        $chatUnread = app(\App\Services\AgentChatService::class)->unreadCountForAgent($currentAgent->id);
    @endphp
    <div class="page">
        @include('toapp_agent.partials.sidebar')

        <div class="page-wrapper">
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="portal-page-header">
                        <div class="portal-page-header__title">
                            <div class="page-pretitle">@lang('Agent workspace')</div>
                            <h2 class="page-title">{{ __($pageTitle ?? 'Dashboard') }}</h2>
                        </div>
                        <div class="portal-page-header__actions d-print-none">
                            <a href="{{ route('toapp.agent.chat.index') }}"
                               class="agent-chat-notify-target agent-notify-bell {{ $chatUnread ? 'has-unread' : '' }}"
                               id="agentChatHeaderBell"
                               title="@lang('Notifications')"
                               aria-label="@lang('Notifications')">
                                <i class="ti ti-bell-ringing"></i>
                                <span id="agentChatHeaderBadge" class="agent-notify-bell-count agent-chat-unread-count {{ $chatUnread ? '' : 'd-none' }}" aria-live="polite">{{ $chatUnread > 99 ? '99+' : $chatUnread }}</span>
                            </a>
                            <div class="portal-user-chip">
                                <span class="avatar avatar-sm">{{ strtoupper(substr($currentAgent->username ?? 'A', 0, 1)) }}</span>
                                <span class="portal-user-chip__meta">
                                    <strong class="d-block" style="font-size: 0.85rem;">{{ $currentAgent->name ?? $currentAgent->username }}</strong>
                                    <small class="text-secondary">{{ $currentAgent->license_no ?? '' }}</small>
                                </span>
                                <form action="{{ route('toapp.agent.logout') }}" method="POST" class="ms-1">
                                    @csrf
                                    <button class="btn btn-ghost-secondary btn-icon btn-sm" type="submit" title="@lang('Logout')">
                                        <i class="ti ti-logout"></i>
                                    </button>
                                </form>
                            </div>
                            @include('partials.language_switcher', ['variant' => 'portal'])
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-body">
                <div class="container-xl">
                    @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    @yield('content')

                    @include('partials.site_copyright', ['copyrightVariant' => 'portal'])
                </div>
            </div>
        </div>
    </div>

    @include('partials.cookie_consent')
    @include('partials.language_switcher_assets')

    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js" defer></script>
    @include('toapp_agent.partials.chat_notifications')
    @stack('script-lib')
    @stack('scripts')
    @include('partials.pwa_scripts', [
        'pwaServiceWorker' => asset('service-worker-agent.js'),
        'pwaScope' => '/agent/',
        'pwaInstallUi' => false,
    ])
</body>
</html>
