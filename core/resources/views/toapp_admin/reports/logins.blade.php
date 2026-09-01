@extends('toapp_admin.layouts.app')

@section('content')
@include('toapp_admin.reports.partials.tabs')

<div class="card">
    <div class="card-header portal-list-header">
        <h3 class="card-title">Login history</h3>
    </div>
    <form class="portal-filter-bar" method="GET">
        <div class="portal-filter-field">
            <label class="form-label">Search</label>
            <input type="search" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="username, IP, city, country">
        </div>
        <div class="portal-filter-actions">
            <button class="btn btn-primary" type="submit"><i class="ti ti-search me-1"></i> Search</button>
            <a class="btn btn-sm" href="{{ route('toapp.admin.reports.logins.export', request()->query()) }}"><i class="ti ti-file-export me-1"></i> Export CSV</a>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead><tr><th>User</th><th>IP</th><th>Location</th><th>Device</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($logins as $login)
                    <tr>
                        <td><div class="fw-bold">{{ optional($login->user)->username ?? 'Unknown' }}</div><div class="text-secondary">{{ optional($login->user)->email }}</div></td>
                        <td>{{ $login->user_ip ?? '-' }}</td>
                        <td>{{ trim(($login->city ?? '') . ', ' . ($login->country ?? ''), ', ') ?: '-' }}<div class="text-secondary">{{ $login->country_code }}</div></td>
                        <td>{{ $login->browser ?? '-' }}<div class="text-secondary">{{ $login->os ?? '-' }}</div></td>
                        <td class="text-secondary">{{ optional($login->created_at)->format('M d, Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-secondary py-4">No login history found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logins->hasPages())
        <div class="card-footer">{{ $logins->links() }}</div>
    @endif
</div>
@endsection
