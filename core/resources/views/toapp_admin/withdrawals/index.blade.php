@extends('toapp_admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-header portal-list-header">
        <h3 class="card-title">@lang('Withdrawals')</h3>
    </div>
    <form class="portal-filter-bar" method="GET">
        <div class="portal-filter-field">
            <label class="form-label">@lang('Search')</label>
            <input type="search" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="@lang('trx or username')">
        </div>
        <div class="portal-filter-field">
            <label class="form-label">@lang('Status')</label>
            <select class="form-select form-select-sm" name="status">
                <option value="">@lang('Any')</option>
                <option value="1" @selected(request('status') === '1')>@lang('Approved')</option>
                <option value="2" @selected(request('status') === '2')>@lang('Pending')</option>
                <option value="3" @selected(request('status') === '3')>@lang('Rejected')</option>
            </select>
        </div>
        <div class="portal-filter-actions">
            <button class="btn btn-primary" type="submit"><i class="ti ti-search me-1"></i> @lang('Search')</button>
            <a class="btn btn-sm" href="{{ route('toapp.admin.withdrawals.export', request()->query()) }}"><i class="ti ti-file-export me-1"></i> @lang('Export CSV')</a>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead><tr><th>@lang('Transaction')</th><th>@lang('User')</th><th>@lang('Amount')</th><th>@lang('Method')</th><th>@lang('Receives')</th><th>@lang('Status')</th><th>@lang('Created')</th><th class="w-1"></th></tr></thead>
            <tbody>
                @forelse($withdrawals as $withdrawal)
                    @php
                        $withdrawalStatus = [1 => 'Approved', 2 => 'Pending', 3 => 'Rejected'][$withdrawal->status] ?? null;
                    @endphp
                    <tr>
                        <td><div class="fw-bold">{{ $withdrawal->trx }}</div><div class="text-secondary">#{{ $withdrawal->id }}</div></td>
                        <td><div class="fw-bold">{{ optional($withdrawal->user)->username ?? __('Unknown') }}</div><div class="text-secondary">{{ optional($withdrawal->user)->email }}</div></td>
                        <td>{{ number_format((float) $withdrawal->amount, 2) }}<div class="text-secondary">@lang('charge') {{ number_format((float) $withdrawal->charge, 2) }}</div></td>
                        <td>{{ optional($withdrawal->method)->name ?? '-' }}</td>
                        <td>{{ number_format((float) $withdrawal->final_amount, 2) }} {{ $withdrawal->currency }}</td>
                        <td><span class="badge {{ $withdrawal->status == 1 ? 'bg-success-lt' : ($withdrawal->status == 3 ? 'bg-danger-lt' : 'bg-secondary-lt') }}">{{ $withdrawalStatus ? __($withdrawalStatus) : $withdrawal->status }}</span></td>
                        <td class="text-secondary">{{ optional($withdrawal->created_at)->format('M d, Y H:i') }}</td>
                        <td><a class="btn btn-sm" href="{{ route('toapp.admin.withdrawals.show', $withdrawal) }}">@lang('Review')</a></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-secondary py-4">@lang('No withdrawals found.')</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($withdrawals->hasPages())
        <div class="card-footer">{{ $withdrawals->links() }}</div>
    @endif
</div>
@endsection
