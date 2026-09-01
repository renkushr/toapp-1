@extends('toapp_admin.layouts.app')

@section('content')
<div class="card">
    @include('partials.portal_list_header', [
        'title' => 'Agents',
        'toolbarView' => 'partials.portal_list_toolbar_agents',
    ])
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Agent</th>
                    <th>License</th>
                    <th>Fee %</th>
                    <th>ZIP code coverage</th>
                    <th>Clients</th>
                    <th>Status</th>
                    <th>Updated</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($agents as $agent)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $agent->name }}</div>
                            <div class="text-secondary">{{ '@' . $agent->username }} · {{ $agent->email }}</div>
                        </td>
                        <td><code>{{ $agent->license_no }}</code></td>
                        <td>{{ $agent->feePercentLabel() }}</td>
                        <td class="text-secondary">{{ $agent->zipCoverageSummary($zipCatalogTotal ?? null) }}</td>
                        <td>{{ $agent->users_count }}</td>
                        <td><span class="badge {{ (int) $agent->status === 1 ? 'bg-success-lt' : 'bg-danger-lt' }}">{{ (int) $agent->status === 1 ? 'Active' : 'Disabled' }}</span></td>
                        <td class="text-secondary">{{ optional($agent->updated_at)->format('M d, Y') }}</td>
                        <td><a href="{{ route('toapp.admin.agents.edit', $agent) }}" class="btn btn-sm">Edit</a></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-secondary py-4">No agents yet. <a href="{{ route('toapp.admin.agents.create') }}">Create the first agent</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($agents->hasPages())
        <div class="card-footer">{{ $agents->links() }}</div>
    @endif
</div>

@include('partials.portal_help_modal', [
    'id' => 'agentHelpModal',
    'title' => 'How agent matching works',
    'partial' => 'toapp_admin.agents.partials.help',
])
@endsection
