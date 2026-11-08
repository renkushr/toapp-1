@extends('toapp_admin.layouts.app')

@section('content')
<div class="mb-3">
    <a href="{{ route('toapp.admin.plans.index') }}" class="btn btn-ghost-secondary btn-sm">
        <i class="ti ti-arrow-left me-1"></i> @lang('Back to products')
    </a>
</div>

@include('toapp_admin.plans.partials.form', [
    'plan' => null,
    'portals' => $portals,
    'action' => route('toapp.admin.plans.store'),
    'method' => 'POST',
])
@endsection
