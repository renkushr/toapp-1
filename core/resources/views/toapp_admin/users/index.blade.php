@extends('toapp_admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-header portal-list-header">
        <h3 class="card-title">Users</h3>
    </div>
    <form class="portal-filter-bar" method="GET">
        <div class="portal-filter-field">
            <label class="form-label">Search</label>
            <input type="search" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="username, email, name">
        </div>
        <div class="portal-filter-field">
            <label class="form-label">Status</label>
            <select class="form-select form-select-sm" name="status">
                <option value="">Any</option>
                <option value="1" @selected(request('status') === '1')>Active</option>
                <option value="0" @selected(request('status') === '0')>Banned</option>
            </select>
        </div>
        <div class="portal-filter-field">
            <label class="form-label">Approval</label>
            <select class="form-select form-select-sm" name="approval">
                <option value="">Any</option>
                <option value="pending" @selected(request('approval') === 'pending')>Pending</option>
                <option value="approved" @selected(request('approval') === 'approved')>Approved</option>
                <option value="rejected" @selected(request('approval') === 'rejected')>Rejected</option>
            </select>
        </div>
        <div class="portal-filter-field">
            <label class="form-label">KYC</label>
            <select class="form-select form-select-sm" name="kyc">
                <option value="">Any</option>
                <option value="0" @selected(request('kyc') === '0')>Unverified</option>
                <option value="1" @selected(request('kyc') === '1')>Verified</option>
                <option value="2" @selected(request('kyc') === '2')>Pending</option>
            </select>
        </div>
        <div class="portal-filter-actions">
            <button class="btn btn-primary" type="submit"><i class="ti ti-search me-1"></i> Search</button>
            <a class="btn btn-outline-secondary" href="{{ route('toapp.admin.users.export', request()->query()) }}"><i class="ti ti-file-export me-1"></i> Export CSV</a>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Contact</th>
                    <th>Balance</th>
                    <th>Checks</th>
                    <th>Joined</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ trim($user->firstname . ' ' . $user->lastname) ?: $user->username }}</div>
                            <div class="text-secondary">{{ '@' . $user->username }}</div>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $user->email }}</div>
                            <div class="text-secondary">{{ $user->dial_code }}{{ $user->mobile }}</div>
                        </td>
                        <td>{{ number_format((float) $user->balance, 2) }}</td>
                        <td>
                            <span class="badge {{ $user->status ? 'bg-success-lt' : 'bg-danger-lt' }}">{{ $user->status ? 'Active' : 'Banned' }}</span>
                            @php
                                $approvalClass = $user->approval_status === 'approved' ? 'bg-success-lt' : ($user->approval_status === 'rejected' ? 'bg-danger-lt' : 'bg-warning-lt');
                            @endphp
                            <span class="badge {{ $approvalClass }}">{{ ucfirst($user->approval_status ?? 'approved') }}</span>
                            <span class="badge {{ $user->kv == 1 ? 'bg-success-lt' : 'bg-secondary-lt' }}">KYC {{ $user->kv == 1 ? 'OK' : ($user->kv == 2 ? 'Pending' : 'No') }}</span>
                        </td>
                        <td class="text-secondary">{{ optional($user->created_at)->format('M d, Y') }}</td>
                        <td><a class="btn btn-outline-primary" href="{{ route('toapp.admin.users.show', $user) }}">Open</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-secondary py-4">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="card-footer">{{ $users->links() }}</div>
    @endif
</div>
@endsection
