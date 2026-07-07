@extends('toapp_admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-header portal-list-header">
        <h3 class="card-title">Deposits</h3>
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
                <option value="0" @selected(request('status') === '0')>Initiated</option>
                <option value="1" @selected(request('status') === '1')>Success</option>
                <option value="2" @selected(request('status') === '2')>Pending</option>
                <option value="3" @selected(request('status') === '3')>Rejected</option>
            </select>
        </div>
        <div class="portal-filter-actions">
            <button class="btn btn-primary" type="submit"><i class="ti ti-search me-1"></i> Search</button>
            <a class="btn btn-sm" href="{{ route('toapp.admin.deposits.export', request()->query()) }}"><i class="ti ti-file-export me-1"></i> Export CSV</a>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead><tr><th>Transaction</th><th>User</th><th>Amount</th><th>Method</th><th>Status</th><th>Created</th><th class="w-1"></th></tr></thead>
            <tbody>
                @forelse($deposits as $deposit)
                    <tr>
                        <td><div class="fw-bold">{{ $deposit->trx }}</div><div class="text-secondary">#{{ $deposit->id }}</div></td>
                        <td><div class="fw-bold">{{ optional($deposit->user)->username ?? 'Unknown' }}</div><div class="text-secondary">{{ optional($deposit->user)->email }}</div></td>
                        <td>{{ number_format((float) $deposit->amount, 2) }}<div class="text-secondary">charge {{ number_format((float) $deposit->charge, 2) }}</div></td>
                        <td>{{ optional($deposit->gateway)->name ?? $deposit->method_currency }}</td>
                        <td><span class="badge {{ $deposit->status == 1 ? 'bg-success-lt' : ($deposit->status == 3 ? 'bg-danger-lt' : 'bg-secondary-lt') }}">{{ ['Initiated','Success','Pending','Rejected'][$deposit->status] ?? $deposit->status }}</span></td>
                        <td class="text-secondary">{{ optional($deposit->created_at)->format('M d, Y H:i') }}</td>
                        <td><a class="btn btn-sm" href="{{ route('toapp.admin.deposits.show', $deposit) }}">Review</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-secondary py-4">No deposits found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($deposits->hasPages())
        <div class="card-footer">{{ $deposits->links() }}</div>
    @endif
</div>
@endsection
