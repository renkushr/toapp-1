@extends('toapp_admin.layouts.app')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <a href="{{ route('toapp.admin.plans.index') }}" class="btn btn-ghost-secondary btn-sm">
        <i class="ti ti-arrow-left me-1"></i> Back to products
    </a>
    <span class="badge {{ $plan->status ? 'bg-success-lt' : 'bg-secondary-lt' }}">
        {{ $plan->status ? 'Enabled' : 'Disabled' }}
    </span>
</div>

@include('toapp_admin.plans.partials.form', [
    'plan' => $plan,
    'portals' => $portals,
    'action' => route('toapp.admin.plans.update', $plan),
    'method' => 'PUT',
])
@endsection
