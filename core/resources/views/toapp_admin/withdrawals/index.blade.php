@extends('toapp_admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-header portal-list-header">
        <h3 class="card-title">Withdrawals</h3>
    </div>
    <form class="portal-filter-bar" method="GET">
        <div class="portal-filter-field">
            <label class="form-label">Search</label>
            <input type="search" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="trx or username">
        </div>
        <div class="portal-filter-field">
            <label class="form-label">Status</label>
            <select class="form-select form-select-sm" name="status">
                <option value="">Any</option>
                <option value="1" @selected(request('status') === '1')>Approved</option>
                <option value="2" @selected(request('status') === '2')>Pending</option>
                <option value="3" @selected(request('status') === '3')>Rejected</option>
            </select>
        </div>
        <div class="portal-filter-actions">
            <button class="btn btn-primary" type="submit"><i class="ti ti-search me-1"></i> Search</button>
            <a class="btn btn-sm" href="{{ route('toapp.admin.withdrawals.export', request()->query()) }}"><i class="ti ti-file-export me-1"></i> Export CSV</a>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead><tr><th>Transaction</th><th>User</th><th>Amount</th><th>Method</th><th>Receives</th><th>Status</th><th>Created</th><th class="w-1"></th></tr></thead>
            <tbody>
                @forelse($withdrawals as $withdrawal)
                    <tr>
                        <td><div class="fw-bold">{{ $withdrawal->trx }}</div><div class="text-secondary">#{{ $withdrawal->id }}</div></td>
                        <td><div class="fw-bold">{{ optional($withdrawal->user)->username ?? 'Unknown' }}</div><div class="text-secondary">{{ optional($withdrawal->user)->email }}</div></td>
                        <td>{{ number_format((float) $withdrawal->amount, 2) }}<div class="text-secondary">charge {{ number_format((float) $withdrawal->charge, 2) }}</div></td>
                        <td>{{ optional($withdrawal->method)->name ?? '-' }}</td>
                        <td>{{ number_format((float) $withdrawal->final_amount, 2) }} {{ $withdrawal->currency }}</td>
                        <td><span class="badge {{ $withdrawal->status == 1 ? 'bg-success-lt' : ($withdrawal->status == 3 ? 'bg-danger-lt' : 'bg-secondary-lt') }}">{{ [1 => 'Approved', 2 => 'Pending', 3 => 'Rejected'][$withdrawal->status] ?? $withdrawal->status }}</span></td>
                        <td class="text-secondary">{{ optional($withdrawal->created_at)->format('M d, Y H:i') }}</td>
                        <td><a class="btn btn-sm" href="{{ route('toapp.admin.withdrawals.show', $withdrawal) }}">Review</a></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-secondary py-4">No withdrawals found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($withdrawals->hasPages())
        <div class="card-footer">{{ $withdrawals->links() }}</div>
    @endif
</div>
@endsection
