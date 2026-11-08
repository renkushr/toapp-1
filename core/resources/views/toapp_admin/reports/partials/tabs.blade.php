@php
    $admin = auth('admin')->user();
    $canAgents = $admin?->canAccess('agents');
    $canReports = $admin?->canAccess('reports');
@endphp
<section class="ta-tabs">
    @if($canReports)
        <a @class(['active' => request()->routeIs('toapp.admin.reports.transactions')]) href="{{ route('toapp.admin.reports.transactions') }}">@lang('Transactions')</a>
    @endif
    <a @class(['active' => request()->routeIs('toapp.admin.reports.investments*')]) href="{{ route('toapp.admin.reports.investments') }}">@lang('Investments')</a>
    @if($canAgents)
        <a @class(['active' => request()->routeIs('toapp.admin.reports.agents*')]) href="{{ route('toapp.admin.reports.agents') }}">@lang('Agent Reports')</a>
    @endif
    @if($canReports)
        <a @class(['active' => request()->routeIs('toapp.admin.reports.logins*')]) href="{{ route('toapp.admin.reports.logins') }}">@lang('Login History')</a>
        <a @class(['active' => request()->routeIs('toapp.admin.reports.audits*')]) href="{{ route('toapp.admin.reports.audits') }}">@lang('Audit Logs')</a>
    @endif
</section>
