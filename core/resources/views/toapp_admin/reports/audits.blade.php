@extends('toapp_admin.layouts.app')

@section('content')
<section class="ta-tabs">
    <a href="{{ route('toapp.admin.reports.transactions') }}">Transactions</a>
    <a href="{{ route('toapp.admin.reports.investments') }}">Investments</a>
    <a href="{{ route('toapp.admin.reports.logins') }}">Login History</a>
    <a class="active" href="{{ route('toapp.admin.reports.audits') }}">Audit Logs</a>
</section>

<div class="card">
    <div class="card-header portal-card-header-stacked">
        <h3 class="card-title">Operational audit log</h3>
        <p class="card-subtitle text-secondary mb-0">Admin trail of sensitive actions</p>
    </div>
    <form class="portal-filter-bar" method="GET">
        <div class="portal-filter-field">
            <label class="form-label">Search</label>
            <input type="search" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="action, admin, IP, target">
        </div>
        <div class="portal-filter-actions">
            <button class="btn btn-primary" type="submit"><i class="ti ti-search me-1"></i> Search</button>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr><th>Action</th><th>Admin</th><th>Target</th><th>Context</th><th>IP</th><th>Date</th></tr>
            </thead>
            <tbody>
                @forelse($audits as $audit)
                    <tr>
                        <td><div class="fw-bold">{{ $audit->action }}</div><div class="text-secondary">#{{ $audit->id }}</div></td>
                        <td><div class="fw-bold">{{ optional($audit->admin)->username ?? 'System' }}</div><div class="text-secondary">{{ optional($audit->admin)->email }}</div></td>
                        <td>{{ class_basename($audit->target_type ?? '-') }}<div class="text-secondary">{{ $audit->target_id ? '#' . $audit->target_id : '-' }}</div></td>
                        <td>
                            @if($audit->metadata)
                                <code>{{ json_encode($audit->metadata, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</code>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $audit->ip_address ?? '-' }}</td>
                        <td class="text-secondary">{{ optional($audit->created_at)->format('M d, Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-secondary py-4">No admin activity has been logged yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($audits->hasPages())
        <div class="card-footer">{{ $audits->links() }}</div>
    @endif
</div>
@endsection
