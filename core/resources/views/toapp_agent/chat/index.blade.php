@extends('toapp_agent.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <div class="text-secondary small text-uppercase fw-bold mb-1">@lang('Private chat')</div>
        <h2 class="mb-0">@lang('Conversations')</h2>
    </div>
</div>

<div class="card">
    <div class="list-group list-group-flush agent-chat-list">
        @forelse($conversations as $conversation)
            @php
                $client = $conversation->user;
                $latest = $conversation->latestMessage;
                $preview = $latest?->message;
                if (!$preview && $latest?->attachments?->isNotEmpty()) {
                    $preview = '📎 ' . $latest->attachments->first()->original_name;
                }
                $unread = (int) ($unreadByConversation[$conversation->id] ?? 0);
                $initials = collect([$client->firstname ?? '', $client->lastname ?? ''])
                    ->filter()
                    ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
                    ->take(2)
                    ->implode('') ?: 'CL';
                $chatUrl = route('toapp.agent.chat.show', $client);
            @endphp
            <a href="{{ $chatUrl }}" class="list-group-item list-group-item-action agent-chat-list-item {{ $unread > 0 ? 'has-unread' : '' }}">
                <div class="d-flex align-items-center gap-3">
                    <span class="portal-client-avatar flex-shrink-0">{{ $initials }}</span>
                    <div class="flex-fill min-w-0">
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <strong class="text-truncate">{{ $client->firstname }} {{ $client->lastname }}</strong>
                            <span class="text-secondary small flex-shrink-0">
                                {{ optional($conversation->last_message_at ?? $latest?->created_at)->diffForHumans(short: true) ?: '' }}
                            </span>
                        </div>
                        <div class="text-secondary small text-truncate">
                            @lang('ZIP') {{ $client->zipcode ?: '—' }}
                            @if($preview)
                                · {{ \Illuminate\Support\Str::limit($preview, 48) }}
                            @endif
                        </div>
                    </div>
                    <div class="flex-shrink-0 d-flex align-items-center gap-2">
                        @if($unread > 0)
                            <span class="badge bg-red">{{ $unread > 99 ? '99+' : $unread }}</span>
                        @endif
                        <span class="btn btn-primary btn-sm agent-chat-open-btn">
                            <i class="ti ti-messages d-sm-none"></i>
                            <span class="d-none d-sm-inline">@lang('Open')</span>
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="list-group-item text-secondary text-center py-5">
                @lang('No conversations yet. Open a client profile and start chatting.')
            </div>
        @endforelse
    </div>
</div>

<div class="mt-3">
    {{ $conversations->links() }}
</div>
@endsection

@push('styles')
<style>
.agent-chat-list-item {
    padding: 1rem 1.1rem;
    text-decoration: none;
    color: inherit;
}
.agent-chat-list-item:hover,
.agent-chat-list-item:focus {
    background: rgba(91, 94, 247, 0.06);
    color: inherit;
}
.agent-chat-list-item.has-unread {
    background: rgba(255, 77, 79, 0.04);
}
.agent-chat-list-item .min-w-0 {
    min-width: 0;
}
.agent-chat-open-btn {
    pointer-events: none;
}
@media (max-width: 575.98px) {
    .agent-chat-list-item {
        padding: 0.95rem 0.9rem;
    }
    .agent-chat-open-btn {
        min-width: 44px;
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 12px;
    }
    .agent-chat-open-btn .ti {
        font-size: 1.15rem;
    }
}
</style>
@endpush
