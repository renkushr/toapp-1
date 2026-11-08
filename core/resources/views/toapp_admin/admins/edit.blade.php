@extends('toapp_admin.layouts.app')

@section('content')
<div class="portal-page-actions">
    <a href="{{ route('toapp.admin.admins.index') }}" class="btn btn-ghost-secondary btn-sm"><i class="ti ti-arrow-left me-1"></i> @lang('Back to admins')</a>
</div>
@include('toapp_admin.admins.partials.form', ['admin' => $admin, 'roles' => $roles, 'permissions' => $permissions, 'action' => route('toapp.admin.admins.update', $admin), 'method' => 'PUT'])
@endsection
