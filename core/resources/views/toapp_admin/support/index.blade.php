@extends('toapp_admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-header portal-list-header">
        <h3 class="card-title">@lang('Support tickets')</h3>
    </div>
    <form class="portal-filter-bar" method="GET">
        <div class="portal-filter-field">
            <label class="form-label">@lang('Search')</label>
            <input type="search" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="@lang('ticket, subject, name, email')">
        </div>
        <div class="portal-filter-field">
            <label class="form-label">@lang('Status')</label>
            <select class="form-select form-select-sm" name="status">
                <option value="">@lang('Any')</option>
                <option value="0" @selected(request('status') === '0')>@lang('Open')</option>
                <option value="1" @selected(request('status') === '1')>@lang('Answered')</option>
                <option value="2" @selected(request('status') === '2')>@lang('Customer Reply')</option>
                <option value="3" @selected(request('status') === '3')>@lang('Closed')</option>
            </select>
        </div>
        <div class="portal-filter-actions">
            <button class="btn btn-primary" type="submit"><i class="ti ti-search me-1"></i> @lang('Search')</button>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead><tr><th>@lang('Ticket')</th><th>@lang('Customer')</th><th>@lang('Subject')</th><th>@lang('Priority')</th><th>@lang('Status')</th><th>@lang('Last Reply')</th><th class="w-1"></th></tr></thead>
            <tbody>
                @forelse($tickets as $ticket)
                    @php
                        $priorityLabel = [1 => 'Low', 2 => 'Medium', 3 => 'High'][$ticket->priority] ?? 'Normal';
                        $statusLabel = [0 => 'Open', 1 => 'Answered', 2 => 'Customer Reply', 3 => 'Closed'][$ticket->status] ?? null;
                    @endphp
                    <tr>
                        <td><div class="fw-bold">#{{ $ticket->ticket }}</div><div class="text-secondary">@lang('ID') {{ $ticket->id }}</div></td>
                        <td><div class="fw-bold">{{ optional($ticket->user)->username ?? $ticket->name }}</div><div class="text-secondary">{{ $ticket->email }}</div></td>
                        <td>{{ $ticket->subject }}</td>
                        <td><span class="badge {{ $ticket->priority == 3 ? 'bg-danger-lt' : 'bg-secondary-lt' }}">{{ __($priorityLabel) }}</span></td>
                        <td><span class="badge {{ $ticket->status == 3 ? 'bg-secondary-lt' : ($ticket->status == 1 ? 'bg-success-lt' : 'bg-warning-lt') }}">{{ $statusLabel ? __($statusLabel) : $ticket->status }}</span></td>
                        <td class="text-secondary">{{ $ticket->last_reply ? \Carbon\Carbon::parse($ticket->last_reply)->format('M d, Y H:i') : optional($ticket->updated_at)->format('M d, Y H:i') }}</td>
                        <td><a class="btn btn-sm" href="{{ route('toapp.admin.support.show', $ticket) }}">@lang('Open')</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-secondary py-4">@lang('No support tickets found.')</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tickets->hasPages())
        <div class="card-footer">{{ $tickets->links() }}</div>
    @endif
</div>
@endsection
