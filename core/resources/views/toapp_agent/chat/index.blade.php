@extends('toapp_agent.layouts.app')

@section('content')
<section class="ta-panel">
    <div class="ta-panel-head">
        <div><span class="ta-kicker">Private chat</span><h2>Conversations</h2></div>
    </div>
    <div class="ta-table-wrap">
        <table class="ta-table">
            <thead><tr><th>Client</th><th>ZIP code</th><th>Last message</th><th>Unread</th><th></th></tr></thead>
            <tbody>
                @forelse($conversations as $conversation)
                    @php
                        $client = $conversation->user;
                        $latest = $conversation->latestMessage;
                        $preview = $latest?->message;
                        if (!$preview && $latest?->attachments?->isNotEmpty()) {
                            $preview = '📎 ' . $latest->attachments->first()->original_name;
                        }
                        $unread = (int) ($unreadByConversation[$conversation->id] ?? 0);
                    @endphp
                    <tr>
                        <td><strong>{{ $client->firstname }} {{ $client->lastname }}</strong><small>{{ $client->email }}</small></td>
                        <td>{{ $client->zipcode }}</td>
                        <td><small>{{ $preview ? \Illuminate\Support\Str::limit($preview, 60) : '—' }}</small></td>
                        <td>
                            @if($unread > 0)
                                <span class="badge bg-red">{{ $unread }}</span>
                            @else
                                <span class="text-secondary">—</span>
                            @endif
                        </td>
                        <td><a href="{{ route('toapp.agent.chat.show', $client) }}">Open chat</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5">No conversations yet. Open a client profile and start chatting.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $conversations->links() }}
</section>
@endsection
