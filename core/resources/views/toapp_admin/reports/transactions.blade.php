@extends('toapp_admin.layouts.app')

@section('content')
<section class="ta-tabs">
    <a class="active" href="{{ route('toapp.admin.reports.transactions') }}">Transactions</a>
    <a href="{{ route('toapp.admin.reports.investments') }}">Investments</a>
    <a href="{{ route('toapp.admin.reports.logins') }}">Login History</a>
    <a href="{{ route('toapp.admin.reports.audits') }}">Audit Logs</a>
</section>

<div class="card">
    <div class="card-header portal-list-header">
        <h3 class="card-title">Transactions</h3>
    </div>
    <form class="portal-filter-bar" method="GET">
        <div class="portal-filter-field">
            <label class="form-label">Search</label>
            <input type="search" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="trx, username, detail">
        </div>
        <div class="portal-filter-field">
            <label class="form-label">Type</label>
            <select class="form-select form-select-sm" name="trx_type">
                <option value="">Any</option>
                <option value="+" @selected(request('trx_type') === '+')>Credit</option>
                <option value="-" @selected(request('trx_type') === '-')>Debit</option>
            </select>
        </div>
        <div class="portal-filter-field">
            <label class="form-label">Remark</label>
            <select class="form-select form-select-sm" name="remark">
                <option value="">Any</option>
                @foreach($remarks as $remark)
                    <option value="{{ $remark }}" @selected(request('remark') === $remark)>{{ $remark }}</option>
                @endforeach
            </select>
        </div>
        <div class="portal-filter-actions">
            <button class="btn btn-primary" type="submit"><i class="ti ti-search me-1"></i> Search</button>
            <a class="btn btn-sm" href="{{ route('toapp.admin.reports.transactions.export', request()->query()) }}"><i class="ti ti-file-export me-1"></i> Export CSV</a>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead><tr><th>TRX</th><th>User</th><th>Amount</th><th>Post Balance</th><th>Remark</th><th>Detail</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($transactions as $trx)
                    <tr>
                        <td><div class="fw-bold">{{ $trx->trx ?? '-' }}</div><div class="text-secondary">#{{ $trx->id }}</div></td>
                        <td><div class="fw-bold">{{ optional($trx->user)->username ?? 'System' }}</div><div class="text-secondary">{{ optional($trx->user)->email }}</div></td>
                        <td><span class="{{ $trx->trx_type === '-' ? 'text-danger' : 'text-success' }}">{{ $trx->trx_type }}{{ number_format((float) $trx->amount, 2) }}</span><div class="text-secondary">charge {{ number_format((float) $trx->charge, 2) }}</div></td>
                        <td>{{ number_format((float) $trx->post_balance, 2) }}</td>
                        <td>{{ $trx->remark ?? '-' }}</td>
                        <td>{{ $trx->details ?? '-' }}</td>
                        <td class="text-secondary">{{ optional($trx->created_at)->format('M d, Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-secondary py-4">No transactions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transactions->hasPages())
        <div class="card-footer">{{ $transactions->links() }}</div>
    @endif
</div>
@endsection
