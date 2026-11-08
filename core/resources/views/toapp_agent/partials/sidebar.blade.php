<aside class="navbar navbar-vertical navbar-expand-lg portal-sidebar" data-bs-theme="dark">
    @php
        $agent = auth('agent')->user();
        $chatUnread = app(\App\Services\AgentChatService::class)->unreadCountForAgent($agent->id);
    @endphp
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#agent-sidebar" aria-controls="agent-sidebar" aria-expanded="false" aria-label="@lang('Toggle navigation')">
            <span class="navbar-toggler-icon"></span>
        </button>

        <h1 class="navbar-brand navbar-brand-autodark mb-0">
            @include('partials.portal_brand_logo', [
                'href' => route('toapp.agent.dashboard'),
                'variant' => 'sidebar',
            ])
        </h1>

        <div class="collapse navbar-collapse" id="agent-sidebar">
            <ul class="navbar-nav pt-lg-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('toapp.agent.dashboard') ? 'active' : '' }}" href="{{ route('toapp.agent.dashboard') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-dashboard"></i></span>
                        <span class="nav-link-title">@lang('Dashboard')</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('toapp.agent.users.*') ? 'active' : '' }}" href="{{ route('toapp.agent.users.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-users"></i></span>
                        <span class="nav-link-title">@lang('My Clients')</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link agent-chat-notify-target {{ request()->routeIs('toapp.agent.chat.*') ? 'active' : '' }} {{ $chatUnread ? 'has-unread' : '' }}"
                       href="{{ route('toapp.agent.chat.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block agent-chat-nav-icon">
                            <i class="ti ti-messages"></i>
                            <span id="agentChatUnreadBadge" class="agent-chat-sidebar-badge {{ $chatUnread ? '' : 'd-none' }}">
                                <span class="agent-chat-unread-count">{{ $chatUnread > 99 ? '99+' : $chatUnread }}</span>
                            </span>
                        </span>
                        <span class="nav-link-title">@lang('Chat')</span>
                        <span class="agent-chat-sidebar-pill {{ $chatUnread ? '' : 'd-none' }}">
                            <i class="ti ti-bell-ringing"></i>
                            <span class="agent-chat-unread-count">{{ $chatUnread > 99 ? '99+' : $chatUnread }}</span>
                            <span class="agent-chat-sidebar-pill-label">@lang('new')</span>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('toapp.agent.reports.*') ? 'active' : '' }}" href="{{ route('toapp.agent.reports.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-chart-bar"></i></span>
                        <span class="nav-link-title">@lang('Reports')</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('toapp.agent.applications.*') ? 'active' : '' }}" href="{{ route('toapp.agent.applications.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-file-certificate"></i></span>
                        <span class="nav-link-title">@lang('Applications')</span>
                    </a>
                </li>
            </ul>

            <div class="mt-auto p-3">
                <div class="card card-sm portal-sidebar-card">
                    <div class="card-body">
                        <div class="text-secondary text-uppercase fw-bold" style="font-size: 11px;">@lang('Coverage')</div>
                        @php $sidebarCoverage = $agent->zipCoverageByState(); @endphp
                        <div class="text-white mt-1">
                            @if($sidebarCoverage['is_all_usa'])
                                @lang('All USA')
                            @elseif($sidebarCoverage['state_count'] > 0)
                                {{ $sidebarCoverage['state_count'] }} {{ \Illuminate\Support\Str::plural(__('state'), $sidebarCoverage['state_count']) }}
                            @else
                                @lang('No coverage')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>
