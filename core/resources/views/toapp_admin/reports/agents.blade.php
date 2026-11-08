@extends('toapp_admin.layouts.app')

@section('content')
@include('toapp_admin.reports.partials.tabs')

<div class="row row-deck row-cards mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-id-badge-2"></i></span>
                <div class="subheader">@lang('Agents')</div>
                <div class="h1 mb-0">{{ $totals['agents'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-users"></i></span>
                <div class="subheader">@lang('Assigned clients')</div>
                <div class="h1 mb-0">{{ $totals['clients_total'] }}</div>
                <div class="text-secondary">{{ $totals['clients_active'] }} @lang('active')</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-currency-dollar"></i></span>
                <div class="subheader">@lang('Investment sales')</div>
                <div class="h1 mb-0">{{ showAmount($totals['investment_sales_amount']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-file-certificate"></i></span>
                <div class="subheader">@lang('Insurance confirmed')</div>
                <div class="h1 mb-0">{{ showAmount($totals['insurance_confirmed_annual']) }}</div>
                <div class="text-secondary">@lang('annual quoted')</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-receipt-2"></i></span>
                <div class="subheader">@lang('Platform fees due')</div>
                <div class="h1 mb-0">{{ showAmount($totals['platform_fee_amount']) }}</div>
                <div class="text-secondary">@lang('from agent fee %')</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header portal-list-header">
        <h3 class="card-title">@lang('Agent performance')</h3>
    </div>
    <form class="portal-filter-bar" method="GET">
        <div class="portal-filter-field">
            <label class="form-label">@lang('Search')</label>
            <input type="search" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="@lang('name, email, license')">
        </div>
        <div class="portal-filter-field">
            <label class="form-label">@lang('Activity period')</label>
            <select class="form-select form-select-sm" name="days">
                <option value="7" @selected($days === 7)>@lang('Last 7 days')</option>
                <option value="30" @selected($days === 30)>@lang('Last 30 days')</option>
                <option value="90" @selected($days === 90)>@lang('Last 90 days')</option>
                <option value="0" @selected($days === 0)>@lang('All time')</option>
            </select>
        </div>
        <div class="portal-filter-actions">
            <button class="btn btn-primary" type="submit"><i class="ti ti-search me-1"></i> @lang('Filter')</button>
            <a class="btn btn-sm" href="{{ route('toapp.admin.reports.agents.export', request()->query()) }}"><i class="ti ti-file-export me-1"></i> @lang('Export CSV')</a>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>@lang('Agent')</th>
                    <th>@lang('Clients')</th>
                    <th>@lang('Activity')</th>
                    <th>@lang('Investment sales')</th>
                    <th>@lang('Insurance sales')</th>
                    <th>@lang('Agent fee')</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $row)
                    @php
                        $agent = $row['agent'];
                        $m = $row['metrics'];
                    @endphp
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $agent->name }}</div>
                            <div class="text-secondary">{{ $agent->license_no }} · {{ $agent->email }}</div>
                            <span class="badge {{ $agent->status ? 'bg-success-lt' : 'bg-secondary-lt' }}">{{ $agent->status ? __('Active') : __('Inactive') }}</span>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $m['clients_total'] }} @lang('total')</div>
                            <div class="text-secondary">{{ $m['clients_active'] }} @lang('active') · {{ $m['clients_approved'] }} @lang('approved')</div>
                            <div class="text-secondary">{{ $m['active_products'] }} @lang('running products')</div>
                        </td>
                        <td>
                            <div class="text-secondary">{{ $m['logins_count'] }} @lang('client logins')</div>
                            <div class="text-secondary">{{ $m['chat_messages_user'] }} @lang('client msgs') · {{ $m['chat_messages_agent'] }} @lang('agent msgs')</div>
                            <div class="text-secondary">{{ $m['conversations_count'] }} @lang('conversations')</div>
                        </td>
                        <td>
                            <div class="fw-bold">{{ showAmount($m['investment_sales_amount']) }}</div>
                            <div class="text-secondary">{{ $m['investment_sales_count'] }} @lang('approved/running')</div>
                            <div class="text-secondary">{{ $m['investment_pending_count'] }} @lang('pending') ({{ showAmount($m['investment_pending_amount']) }})</div>
                        </td>
                        <td>
                            <div class="fw-bold">{{ showAmount($m['insurance_confirmed_annual']) }}</div>
                            <div class="text-secondary">{{ $m['insurance_confirmed_count'] }} @lang('confirmed')</div>
                            <div class="text-secondary">{{ $m['insurance_pipeline_count'] }} @lang('in pipeline')</div>
                        </td>
                        <td>
                            <div class="fw-bold">{{ showAmount($m['platform_fee_amount']) }}</div>
                            <div class="text-secondary">@lang('fee') {{ $agent->feePercentLabel() }}</div>
                        </td>
                        <td>
                            <a class="btn btn-sm" href="{{ route('toapp.admin.reports.agents.show', [$agent, 'days' => $days]) }}">@lang('Details')</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-secondary">@lang('No agents found.')</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
