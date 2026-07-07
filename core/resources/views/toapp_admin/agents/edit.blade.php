@extends('toapp_admin.layouts.app')

@section('content')
<div class="portal-page-actions">
    <a href="{{ route('toapp.admin.agents.index') }}" class="btn btn-ghost-secondary btn-sm"><i class="ti ti-arrow-left me-1"></i> Back to agents</a>
    <span class="badge {{ (int) $agent->status === 1 ? 'bg-success-lt' : 'bg-danger-lt' }}">{{ (int) $agent->status === 1 ? 'Active' : 'Disabled' }}</span>
</div>

@include('toapp_admin.agents.partials.form', [
    'agent' => $agent,
    'action' => route('toapp.admin.agents.update', $agent),
    'method' => 'PUT',
])
@endsection
