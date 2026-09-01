@extends('toapp_admin.layouts.app')

@section('content')
@include('toapp_admin.reports.partials.tabs')

<div class="card">
    <div class="card-header portal-list-header">
        <h3 class="card-title">Investments</h3>
    </div>
    <form class="portal-filter-bar" method="GET">
        <div class="portal-filter-field">
            <label class="form-label">Search</label>
            <input type="search" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="trx, user, plan">
        </div>
        <div class="portal-filter-field">
            <label class="form-label">Status</label>
            <select class="form-select form-select-sm" name="status">
                <option value="">Any</option>
                <option value="0" @selected(request('status') === '0')>Pending</option>
                <option value="2" @selected(request('status') === '2')>Running</option>
                <option value="1" @selected(request('status') === '1')>Completed</option>
                <option value="3" @selected(request('status') === '3')>Rejected</option>
            </select>
        </div>
        <div class="portal-filter-actions">
            <button class="btn btn-primary" type="submit"><i class="ti ti-search me-1"></i> Search</button>
            <a class="btn btn-sm" href="{{ route('toapp.admin.reports.investments.export', request()->query()) }}"><i class="ti ti-file-export me-1"></i> Export CSV</a>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead><tr><th>Investment</th><th>User</th><th>Plan</th><th>Amount</th><th>Return</th><th>Status</th><th>Dates</th><th class="w-1">Action</th></tr></thead>
            <tbody>
                @forelse($investments as $investment)
                    @php
                        $statusBadge = match ((int) $investment->status) {
                            0 => 'bg-warning-lt',
                            2 => 'bg-success-lt',
                            3 => 'bg-danger-lt',
                            default => 'bg-secondary-lt',
                        };
                        $statusLabel = match ((int) $investment->status) {
                            0 => 'Pending',
                            2 => 'Running',
                            3 => 'Rejected',
                            default => 'Completed',
                        };
                    @endphp
                    <tr>
                        <td><div class="fw-bold">{{ $investment->trx ?? '-' }}</div><div class="text-secondary">#{{ $investment->id }}</div></td>
                        <td><div class="fw-bold">{{ optional($investment->user)->username ?? 'Unknown' }}</div><div class="text-secondary">{{ optional($investment->user)->email }}</div></td>
                        <td>{{ optional($investment->plan)->name ?? '-' }}</td>
                        <td>{{ number_format((float) $investment->amount, 2) }}</td>
                        <td>{{ number_format((float) $investment->interest_amount, 2) }}<div class="text-secondary">{{ $investment->total_paid }}/{{ $investment->total_return }} paid</div></td>
                        <td><span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span>@if($investment->rejection_reason)<div class="text-secondary">{{ $investment->rejection_reason }}</div>@endif</td>
                        <td><div class="fw-bold">{{ $investment->next_return_date ? \Carbon\Carbon::parse($investment->next_return_date)->format('M d, Y') : '-' }}</div><div class="text-secondary">approved {{ $investment->approved_at ? $investment->approved_at->format('M d, Y') : '-' }}</div></td>
                        <td>
                            @if((int) $investment->status === 0 && $admin?->canAccess('investments'))
                                <a href="{{ route('toapp.admin.reports.investments.show', $investment) }}" class="btn btn-sm btn-primary">Review</a>
                            @else
                                <a href="{{ route('toapp.admin.reports.investments.show', $investment) }}" class="btn btn-sm">View</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-secondary py-4">No investments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($investments->hasPages())
        <div class="card-footer">{{ $investments->links() }}</div>
    @endif
</div>
@endsection
