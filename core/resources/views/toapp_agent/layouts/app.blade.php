<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle ?? 'Agent' }} - TOAPP</title>
    <link rel="shortcut icon" type="image/png" href="{{ siteFavicon() }}">
    @include('partials.tabler_assets')
    @stack('styles')
</head>
<body>
    @php
        $currentAgent = auth('agent')->user();
        $chatUnread = app(\App\Services\AgentChatService::class)->unreadCountForAgent($currentAgent->id);
    @endphp
    <div class="page">
        @include('toapp_agent.partials.sidebar')

        <div class="page-wrapper">
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <div class="page-pretitle">Agent workspace</div>
                            <h2 class="page-title">{{ $pageTitle ?? 'Dashboard' }}</h2>
                        </div>
                        <div class="col-auto ms-auto d-print-none">
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('toapp.agent.chat.index') }}"
                                   class="agent-chat-notify-target agent-notify-bell {{ $chatUnread ? 'has-unread' : '' }}"
                                   id="agentChatHeaderBell"
                                   title="Chat notifications"
                                   aria-label="Chat notifications">
                                    <i class="ti ti-bell-ringing"></i>
                                    <span id="agentChatHeaderBadge" class="agent-notify-bell-count {{ $chatUnread ? '' : 'd-none' }}">{{ $chatUnread > 99 ? '99+' : $chatUnread }}</span>
                                </a>
                                <div class="portal-user-chip">
                                <span class="avatar avatar-sm">{{ strtoupper(substr($currentAgent->username ?? 'A', 0, 1)) }}</span>
                                <span>
                                    <strong class="d-block" style="font-size: 0.85rem;">{{ $currentAgent->name ?? $currentAgent->username }}</strong>
                                    <small class="text-secondary">{{ $currentAgent->license_no ?? '' }}</small>
                                </span>
                                <form action="{{ route('toapp.agent.logout') }}" method="POST" class="ms-1">
                                    @csrf
                                    <button class="btn btn-ghost-secondary btn-icon btn-sm" type="submit" title="Logout">
                                        <i class="ti ti-logout"></i>
                                    </button>
                                </form>
                                </div>
                            </div>
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
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js" defer></script>
    @include('toapp_agent.partials.chat_notifications')
    @stack('script-lib')
    @stack('scripts')
</body>
</html>
