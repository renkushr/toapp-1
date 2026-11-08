<aside class="navbar navbar-vertical navbar-expand-lg portal-sidebar" data-bs-theme="dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#admin-sidebar" aria-controls="admin-sidebar" aria-expanded="false" aria-label="@lang('Toggle navigation')">
            <span class="navbar-toggler-icon"></span>
        </button>

        <h1 class="navbar-brand navbar-brand-autodark mb-0">
            @include('partials.portal_brand_logo', [
                'href' => route('toapp.admin.dashboard'),
                'variant' => 'sidebar',
            ])
        </h1>

        <div class="collapse navbar-collapse" id="admin-sidebar">
            @php $admin = auth('admin')->user(); @endphp
            <ul class="navbar-nav pt-lg-3">
                @if($admin?->canAccess('dashboard'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('toapp.admin.dashboard') ? 'active' : '' }}" href="{{ route('toapp.admin.dashboard') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-dashboard"></i></span>
                            <span class="nav-link-title">@lang('Dashboard')</span>
                        </a>
                    </li>
                @endif
                @if($admin?->canAccess('admins'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('toapp.admin.admins.*') ? 'active' : '' }}" href="{{ route('toapp.admin.admins.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-shield-lock"></i></span>
                            <span class="nav-link-title">@lang('Admins')</span>
                        </a>
                    </li>
                @endif
                @if($admin?->canAccess('users'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('toapp.admin.users.*') ? 'active' : '' }}" href="{{ route('toapp.admin.users.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-users"></i></span>
                            <span class="nav-link-title">@lang('Users')</span>
                        </a>
                    </li>
                @endif
                @if($admin?->canAccess('plans'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('toapp.admin.plans.*') ? 'active' : '' }}" href="{{ route('toapp.admin.plans.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-packages"></i></span>
                            <span class="nav-link-title">@lang('Products')</span>
                        </a>
                    </li>
                @endif
                @if($admin?->canAccess('reports'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('toapp.admin.applications.*') ? 'active' : '' }}" href="{{ route('toapp.admin.applications.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-file-certificate"></i></span>
                            <span class="nav-link-title">@lang('Applications')</span>
                        </a>
                    </li>
                @endif
                @if($admin?->canAccess('deposits'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('toapp.admin.deposits.*') ? 'active' : '' }}" href="{{ route('toapp.admin.deposits.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-wallet"></i></span>
                            <span class="nav-link-title">@lang('Deposits')</span>
                        </a>
                    </li>
                @endif
                @if($admin?->canAccess('withdrawals'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('toapp.admin.withdrawals.*') ? 'active' : '' }}" href="{{ route('toapp.admin.withdrawals.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-building-bank"></i></span>
                            <span class="nav-link-title">@lang('Withdrawals')</span>
                        </a>
                    </li>
                @endif
                @if($admin?->canAccess('reports') || $admin?->canAccess('investments'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('toapp.admin.reports.*') ? 'active' : '' }}" href="{{ $admin?->canAccess('reports') ? route('toapp.admin.reports.transactions') : route('toapp.admin.reports.investments') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-chart-line"></i></span>
                            <span class="nav-link-title">@lang('Reports')</span>
                        </a>
                    </li>
                @endif
                @if($admin?->canAccess('referrals'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('toapp.admin.referrals.*') ? 'active' : '' }}" href="{{ route('toapp.admin.referrals.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-affiliate"></i></span>
                            <span class="nav-link-title">@lang('Referrals')</span>
                        </a>
                    </li>
                @endif
                @if($admin?->canAccess('agents'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('toapp.admin.reports.agents*') ? 'active' : '' }}" href="{{ route('toapp.admin.reports.agents') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-chart-bar"></i></span>
                            <span class="nav-link-title">@lang('Agent Reports')</span>
                        </a>
                    </li>
                @endif
                @if($admin?->canAccess('agents'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('toapp.admin.agents.*') ? 'active' : '' }}" href="{{ route('toapp.admin.agents.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-id-badge-2"></i></span>
                            <span class="nav-link-title">@lang('Agents')</span>
                        </a>
                    </li>
                @endif
                @if($admin?->canAccess('support'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('toapp.admin.support.*') ? 'active' : '' }}" href="{{ route('toapp.admin.support.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-headset"></i></span>
                            <span class="nav-link-title">@lang('Support')</span>
                        </a>
                    </li>
                @endif
                @if($admin?->canAccess('methods'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('toapp.admin.methods.*') ? 'active' : '' }}" href="{{ route('toapp.admin.methods.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-credit-card"></i></span>
                            <span class="nav-link-title">@lang('Methods')</span>
                        </a>
                    </li>
                @endif
                @if($admin?->canAccess('settings'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('toapp.admin.cms.about*') ? 'active' : '' }}" href="{{ route('toapp.admin.cms.about') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-info-circle"></i></span>
                            <span class="nav-link-title">@lang('About Us')</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('toapp.admin.settings.edit') ? 'active' : '' }}" href="{{ route('toapp.admin.settings.edit') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-settings"></i></span>
                            <span class="nav-link-title">@lang('Settings')</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('toapp.admin.settings.email*') ? 'active' : '' }}" href="{{ route('toapp.admin.settings.email') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-mail"></i></span>
                            <span class="nav-link-title">@lang('Email / SMTP')</span>
                        </a>
                    </li>
                @endif
                @if($admin?->canAccess('readiness'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('toapp.admin.readiness.*') ? 'active' : '' }}" href="{{ route('toapp.admin.readiness.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-rocket"></i></span>
                            <span class="nav-link-title">@lang('Readiness')</span>
                        </a>
                    </li>
                @endif
            </ul>

            <div class="mt-auto p-3">
                <div class="card card-sm portal-sidebar-card">
                    <div class="card-body">
                        <div class="text-secondary text-uppercase fw-bold" style="font-size: 11px;">@lang('Operations')</div>
                        <div class="text-white mt-1">@lang('Launch backend online')</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>
