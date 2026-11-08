@extends('toapp_agent.layouts.app')

@section('content')
@php
    $coverage = $agent->zipCoverageByState();
@endphp
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">@lang('Dashboard')</h2>
        <p class="text-secondary mb-0">@lang('Overview of your assigned clients and activity.')</p>
    </div>
    <a href="{{ route('toapp.agent.reports.index') }}" class="btn btn-primary btn-sm"><i class="ti ti-chart-bar me-1"></i> @lang('Full report')</a>
</div>

<div class="row row-deck row-cards mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-users"></i></span>
                <div class="subheader">@lang('Assigned clients')</div>
                <div class="h1 mb-0">{{ $stats['assigned_users'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-map-pin"></i></span>
                <div class="subheader">@lang('States covered')</div>
                <div class="h1 mb-0">{{ $coverage['is_all_usa'] ? __('USA') : $coverage['state_count'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-packages"></i></span>
                <div class="subheader">@lang('Active products')</div>
                <div class="h1 mb-0">{{ $stats['active_products'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-messages"></i></span>
                <div class="subheader">@lang('Conversations')</div>
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
                    <div class="subheader">@lang('Agent fee')</div>
                    <div class="h2 mb-0">{{ $agent->feePercentLabel() }} <span class="text-secondary fs-4 fw-normal">@lang('per product sale')</span></div>
                </div>
                <span class="badge bg-secondary-lt">@lang('Set by admin · read only')</span>
            </div>
        </div>
    </div>
</div>

<div class="row row-cards mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Your coverage')</h3>
                @if($coverage['total_zips'] > 0)
                    <div class="card-actions">
                        <span class="text-secondary small">
                            @if($coverage['is_all_usa'])
                                @lang('All USA') · {{ number_format($coverage['total_zips']) }} @lang('ZIP codes')
                            @else
                                {{ $coverage['state_count'] }} {{ \Illuminate\Support\Str::plural(__('state'), $coverage['state_count']) }}
                                · @lang('tap a state to see ZIP codes')
                            @endif
                        </span>
                    </div>
                @endif
            </div>
            <div class="card-body">
                @if($coverage['total_zips'] === 0)
                    <p class="mb-0 text-secondary">@lang('No coverage assigned yet.')</p>
                @else
                    <div class="agent-coverage-list">
                        @if($coverage['is_all_usa'])
                            <div class="agent-coverage-state">
                                <div class="agent-coverage-summary" style="cursor:default">
                                    <span class="fw-bold">@lang('All USA')</span>
                                    <span class="text-secondary">{{ number_format($coverage['total_zips']) }} @lang('ZIP codes')</span>
                                </div>
                            </div>
                        @endif
                        @foreach($coverage['states'] as $state)
                            @if($coverage['is_all_usa'] || empty($state['zips']))
                                <div class="agent-coverage-state">
                                    <div class="agent-coverage-summary" style="cursor:default">
                                        <span class="fw-bold">{{ $state['name'] }}</span>
                                        <span class="text-secondary">{{ number_format($state['count']) }} {{ $state['count'] === 1 ? __('ZIP') : __('ZIPs') }}</span>
                                    </div>
                                </div>
                            @else
                                <details class="agent-coverage-state">
                                    <summary class="agent-coverage-summary">
                                        <span class="fw-bold">{{ $state['name'] }}</span>
                                        <span class="text-secondary">{{ number_format($state['count']) }} {{ $state['count'] === 1 ? __('ZIP') : __('ZIPs') }}</span>
                                    </summary>
                                    <div class="agent-coverage-zip-list">{{ implode(', ', $state['zips']) }}</div>
                                </details>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">@lang('Latest clients')</h3>
        <div class="card-actions">
            <a href="{{ route('toapp.agent.users.index') }}" class="btn btn-primary btn-sm">@lang('View all')</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>@lang('Client')</th>
                    <th>@lang('ZIP code')</th>
                    <th>@lang('Status')</th>
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
                        <td><span class="badge bg-secondary-lt">{{ __(ucfirst($client->approval_status ?? 'pending')) }}</span></td>
                        <td><a href="{{ route('toapp.agent.users.show', $client) }}" class="btn btn-sm">@lang('View')</a></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-secondary">@lang('No clients assigned yet.')</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<style>
.agent-coverage-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
.agent-coverage-state {
    border: 1px solid var(--tblr-border-color, #e6e7e9);
    border-radius: 10px;
    background: var(--tblr-bg-surface, #fff);
    overflow: hidden;
}
.agent-coverage-state-nested {
    margin-bottom: 0.5rem;
}
.agent-coverage-summary {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    padding: 0.85rem 1rem;
    cursor: pointer;
    list-style: none;
}
.agent-coverage-summary::-webkit-details-marker {
    display: none;
}
.agent-coverage-summary::after {
    display: none;
}
details.agent-coverage-state > .agent-coverage-summary::after {
    display: inline-block;
    content: "▸";
    color: var(--tblr-secondary, #667382);
    transition: transform .15s ease;
}
details.agent-coverage-state[open] > .agent-coverage-summary::after {
    transform: rotate(90deg);
}
.agent-coverage-zip-list,
.agent-coverage-zips {
    padding: 0 1rem 1rem;
    color: var(--tblr-secondary, #667382);
    font-size: 0.875rem;
    line-height: 1.5;
    word-break: break-word;
}
</style>
@endpush
