@extends('toapp_agent.layouts.app')

@section('content')
<section class="ta-panel">
    <form class="ta-toolbar ta-toolbar-slim" method="GET">
        <label class="ta-field compact"><span>Search</span><input type="search" name="search" value="{{ request('search') }}" placeholder="name, email, phone"></label>
        <label class="ta-field compact"><span>ZIP code</span><input type="search" name="zip" value="{{ request('zip') }}" placeholder="90210"></label>
        <button class="ta-secondary-btn" type="submit"><i class="las la-search"></i> Filter</button>
    </form>

    <div class="ta-table-wrap">
        <table class="ta-table">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>ZIP code</th>
                    <th>Phone</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $client)
                    <tr>
                        <td>
                            <strong>{{ $client->firstname }} {{ $client->lastname }}</strong>
                            <small>{{ $client->email }}</small>
                        </td>
                        <td>{{ $client->zipcode }}</td>
                        <td>{{ $client->mobile ?: '—' }}</td>
                        <td>{{ $client->investments_count }}</td>
                        <td><span class="ta-badge muted">{{ ucfirst($client->approval_status ?? 'pending') }}</span></td>
                        <td class="ta-row-actions">
                            <a href="{{ route('toapp.agent.users.show', $client) }}">Profile</a>
                            <a href="{{ route('toapp.agent.chat.show', $client) }}">Chat</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No clients in your assigned ZIP code areas yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $users->links() }}
</section>
@endsection
