@extends('toapp_agent.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Dashboard</h2>
        <p class="text-secondary mb-0">Overview of your assigned clients and activity.</p>
    </div>
    <a href="{{ route('toapp.agent.reports.index') }}" class="btn btn-primary btn-sm"><i class="ti ti-chart-bar me-1"></i> Full report</a>
</div>

<div class="row row-deck row-cards mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-users"></i></span>
                <div class="subheader">Assigned clients</div>
                <div class="h1 mb-0">{{ $stats['assigned_users'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-map-pin"></i></span>
                <div class="subheader">ZIP codes</div>
                <div class="h1 mb-0">{{ $stats['zip_codes'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-packages"></i></span>
                <div class="subheader">Active products</div>
                <div class="h1 mb-0">{{ $stats['active_products'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-messages"></i></span>
                <div class="subheader">Conversations</div>
                <div class="h1 mb-0">{{ $stats['open_chats'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row row-cards mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <div class="subheader">Agent fee</div>
                    <div class="h2 mb-0">{{ $agent->feePercentLabel() }} <span class="text-secondary fs-4 fw-normal">per product sale</span></div>
                </div>
                <span class="badge bg-secondary-lt">Set by admin · read only</span>
            </div>
        </div>
    </div>
</div>

<div class="row row-cards mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Your coverage</h3>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $agent->zipCodeList() ?: 'No ZIP codes assigned yet.' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Latest clients</h3>
        <div class="card-actions">
            <a href="{{ route('toapp.agent.users.index') }}" class="btn btn-primary btn-sm">View all</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>ZIP code</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentUsers as $client)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $client->firstname }} {{ $client->lastname }}</div>
                            <div class="text-secondary">{{ $client->email }}</div>
                        </td>
                        <td>{{ $client->zipcode }}</td>
                        <td><span class="badge bg-secondary-lt">{{ ucfirst($client->approval_status ?? 'pending') }}</span></td>
                        <td><a href="{{ route('toapp.agent.users.show', $client) }}" class="btn btn-sm">View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-secondary">No clients assigned yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
