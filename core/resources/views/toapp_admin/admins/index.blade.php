@extends('toapp_admin.layouts.app')

@section('content')
<div class="card">
    @include('partials.portal_list_header', [
        'title' => __('Admin accounts'),
        'toolbarView' => 'partials.portal_list_toolbar_admins',
    ])
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>@lang('Admin')</th>
                    <th>@lang('Role')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Permissions')</th>
                    <th>@lang('Updated')</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $admin->name ?: $admin->username }}</div>
                            <div class="text-secondary">{{ '@' . $admin->username }} · {{ $admin->email }}</div>
                        </td>
                        <td><span class="badge {{ $admin->role === 'super_admin' ? 'bg-primary-lt' : 'bg-secondary-lt' }}">{{ $admin->roleLabel() }}</span></td>
                        <td><span class="badge {{ (int) $admin->status === 1 ? 'bg-success-lt' : 'bg-danger-lt' }}">{{ (int) $admin->status === 1 ? __('Active') : __('Disabled') }}</span></td>
                        <td class="text-secondary">{{ implode(', ', $admin->permissions ?: []) ?: __('Role preset only') }}</td>
                        <td class="text-secondary">{{ optional($admin->updated_at)->format('M d, Y H:i') }}</td>
                        <td><a href="{{ route('toapp.admin.admins.edit', $admin) }}" class="btn btn-sm">@lang('Edit')</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-secondary py-4">@lang('No admin accounts found.')</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($admins->hasPages())
        <div class="card-footer">{{ $admins->links() }}</div>
    @endif
</div>

@include('partials.portal_help_modal', [
    'id' => 'adminRoleGuideModal',
    'title' => __('Recommended admin roles'),
    'partial' => 'toapp_admin.admins.partials.role_guide',
])
@endsection
