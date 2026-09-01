@extends('toapp_admin.layouts.app')

@section('content')
<div class="portal-page-actions">
    <a href="{{ route('toapp.admin.agents.index') }}" class="btn btn-ghost-secondary btn-sm"><i class="ti ti-arrow-left me-1"></i> Back to agents</a>
</div>

@include('toapp_admin.agents.partials.form', [
    'agent' => null,
    'action' => route('toapp.admin.agents.store'),
    'method' => 'POST',
    'selectedZipsByState' => $selectedZipsByState ?? [],
])
@endsection
