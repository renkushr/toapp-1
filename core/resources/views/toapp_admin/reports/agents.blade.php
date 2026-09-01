@extends('toapp_admin.layouts.app')

@section('content')
@include('toapp_admin.reports.partials.tabs')

<div class="row row-deck row-cards mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-id-badge-2"></i></span>
                <div class="subheader">Agents</div>
                <div class="h1 mb-0">{{ $totals['agents'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-users"></i></span>
                <div class="subheader">Assigned clients</div>
                <div class="h1 mb-0">{{ $totals['clients_total'] }}</div>
                <div class="text-secondary">{{ $totals['clients_active'] }} active</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-currency-dollar"></i></span>
                <div class="subheader">Investment sales</div>
                <div class="h1 mb-0">{{ showAmount($totals['investment_sales_amount']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-file-certificate"></i></span>
                <div class="subheader">Insurance confirmed</div>
                <div class="h1 mb-0">{{ showAmount($totals['insurance_confirmed_annual']) }}</div>
                <div class="text-secondary">annual quoted</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-receipt-2"></i></span>
                <div class="subheader">Platform fees due</div>
                <div class="h1 mb-0">{{ showAmount($totals['platform_fee_amount']) }}</div>
                <div class="text-secondary">from agent fee %</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header portal-list-header">
        <h3 class="card-title">Agent performance</h3>
    </div>
    <form class="portal-filter-bar" method="GET">
        <div class="portal-filter-field">
            <label class="form-label">Search</label>
            <input type="search" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="name, email, license">
        </div>
        <div class="portal-filter-field">
            <label class="form-label">Activity period</label>
            <select class="form-select form-select-sm" name="days">
                <option value="7" @selected($days === 7)>Last 7 days</option>
                <option value="30" @selected($days === 30)>Last 30 days</option>
                <option value="90" @selected($days === 90)>Last 90 days</option>
                <option value="0" @selected($days === 0)>All time</option>
            </select>
        </div>
        <div class="portal-filter-actions">
            <button class="btn btn-primary" type="submit"><i class="ti ti-search me-1"></i> Filter</button>
            <a class="btn btn-sm" href="{{ route('toapp.admin.reports.agents.export', request()->query()) }}"><i class="ti ti-file-export me-1"></i> Export CSV</a>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Agent</th>
                    <th>Clients</th>
                    <th>Activity</th>
                    <th>Investment sales</th>
                    <th>Insurance sales</th>
                    <th>Agent fee</th>
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
                            <span class="badge {{ $agent->status ? 'bg-success-lt' : 'bg-secondary-lt' }}">{{ $agent->status ? 'Active' : 'Inactive' }}</span>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $m['clients_total'] }} total</div>
                            <div class="text-secondary">{{ $m['clients_active'] }} active · {{ $m['clients_approved'] }} approved</div>
                            <div class="text-secondary">{{ $m['active_products'] }} running products</div>
                        </td>
                        <td>
                            <div class="text-secondary">{{ $m['logins_count'] }} client logins</div>
                            <div class="text-secondary">{{ $m['chat_messages_user'] }} client msgs · {{ $m['chat_messages_agent'] }} agent msgs</div>
                            <div class="text-secondary">{{ $m['conversations_count'] }} conversations</div>
                        </td>
                        <td>
                            <div class="fw-bold">{{ showAmount($m['investment_sales_amount']) }}</div>
                            <div class="text-secondary">{{ $m['investment_sales_count'] }} approved/running</div>
                            <div class="text-secondary">{{ $m['investment_pending_count'] }} pending ({{ showAmount($m['investment_pending_amount']) }})</div>
                        </td>
                        <td>
                            <div class="fw-bold">{{ showAmount($m['insurance_confirmed_annual']) }}</div>
                            <div class="text-secondary">{{ $m['insurance_confirmed_count'] }} confirmed</div>
                            <div class="text-secondary">{{ $m['insurance_pipeline_count'] }} in pipeline</div>
                        </td>
                        <td>
                            <div class="fw-bold">{{ showAmount($m['platform_fee_amount']) }}</div>
                            <div class="text-secondary">fee {{ $agent->feePercentLabel() }}</div>
                        </td>
                        <td>
                            <a class="btn btn-sm" href="{{ route('toapp.admin.reports.agents.show', [$agent, 'days' => $days]) }}">Details</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-secondary">No agents found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
