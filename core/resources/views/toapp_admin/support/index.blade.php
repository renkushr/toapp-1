@extends('toapp_admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-header portal-list-header">
        <h3 class="card-title">Support tickets</h3>
    </div>
    <form class="portal-filter-bar" method="GET">
        <div class="portal-filter-field">
            <label class="form-label">Search</label>
            <input type="search" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="ticket, subject, name, email">
        </div>
        <div class="portal-filter-field">
            <label class="form-label">Status</label>
            <select class="form-select form-select-sm" name="status">
                <option value="">Any</option>
                <option value="0" @selected(request('status') === '0')>Open</option>
                <option value="1" @selected(request('status') === '1')>Answered</option>
                <option value="2" @selected(request('status') === '2')>Customer Reply</option>
                <option value="3" @selected(request('status') === '3')>Closed</option>
            </select>
        </div>
        <div class="portal-filter-actions">
            <button class="btn btn-primary" type="submit"><i class="ti ti-search me-1"></i> Search</button>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead><tr><th>Ticket</th><th>Customer</th><th>Subject</th><th>Priority</th><th>Status</th><th>Last Reply</th><th class="w-1"></th></tr></thead>
            <tbody>
                @forelse($tickets as $ticket)
                    <tr>
                        <td><div class="fw-bold">#{{ $ticket->ticket }}</div><div class="text-secondary">ID {{ $ticket->id }}</div></td>
                        <td><div class="fw-bold">{{ optional($ticket->user)->username ?? $ticket->name }}</div><div class="text-secondary">{{ $ticket->email }}</div></td>
                        <td>{{ $ticket->subject }}</td>
                        <td><span class="badge {{ $ticket->priority == 3 ? 'bg-danger-lt' : 'bg-secondary-lt' }}">{{ [1 => 'Low', 2 => 'Medium', 3 => 'High'][$ticket->priority] ?? 'Normal' }}</span></td>
                        <td><span class="badge {{ $ticket->status == 3 ? 'bg-secondary-lt' : ($ticket->status == 1 ? 'bg-success-lt' : 'bg-warning-lt') }}">{{ [0 => 'Open', 1 => 'Answered', 2 => 'Customer Reply', 3 => 'Closed'][$ticket->status] ?? $ticket->status }}</span></td>
                        <td class="text-secondary">{{ $ticket->last_reply ? \Carbon\Carbon::parse($ticket->last_reply)->format('M d, Y H:i') : optional($ticket->updated_at)->format('M d, Y H:i') }}</td>
                        <td><a class="btn btn-sm" href="{{ route('toapp.admin.support.show', $ticket) }}">Open</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-secondary py-4">No support tickets found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tickets->hasPages())
        <div class="card-footer">{{ $tickets->links() }}</div>
    @endif
</div>
@endsection
