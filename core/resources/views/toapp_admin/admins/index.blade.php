@extends('toapp_admin.layouts.app')

@section('content')
<div class="card">
    @include('partials.portal_list_header', [
        'title' => 'Admin accounts',
        'toolbarView' => 'partials.portal_list_toolbar_admins',
    ])
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Admin</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Permissions</th>
                    <th>Updated</th>
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
                        <td><span class="badge {{ (int) $admin->status === 1 ? 'bg-success-lt' : 'bg-danger-lt' }}">{{ (int) $admin->status === 1 ? 'Active' : 'Disabled' }}</span></td>
                        <td class="text-secondary">{{ implode(', ', $admin->permissions ?: []) ?: 'Role preset only' }}</td>
                        <td class="text-secondary">{{ optional($admin->updated_at)->format('M d, Y H:i') }}</td>
                        <td><a href="{{ route('toapp.admin.admins.edit', $admin) }}" class="btn btn-sm">Edit</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-secondary py-4">No admin accounts found.</td></tr>
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
    'title' => 'Recommended admin roles',
    'partial' => 'toapp_admin.admins.partials.role_guide',
])
@endsection
