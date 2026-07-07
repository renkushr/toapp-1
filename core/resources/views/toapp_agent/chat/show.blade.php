@extends('toapp_agent.layouts.app')

@section('content')
@php
    $clientInitials = collect([$user->firstname ?? '', $user->lastname ?? ''])->filter()->map(fn ($p) => strtoupper(substr($p, 0, 1)))->take(2)->implode('') ?: 'CL';
    $agentInitials = collect(explode(' ', trim(auth('agent')->user()->name ?? 'Agent')))->filter()->map(fn ($p) => strtoupper(substr($p, 0, 1)))->take(2)->implode('') ?: 'AG';
@endphp

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin_new/css/agent-chat.css') }}">
@endpush

<section class="ta-chat-page">
    @if(!filled(config('broadcasting.connections.pusher.key')))
        <div class="alert alert-warning mb-3">Realtime chat uses 5-second polling until Pusher is configured.</div>
    @endif

    <div class="ta-chat-card">
        <div class="ta-chat-header">
            <div class="ta-chat-header-avatar">{{ $clientInitials }}</div>
            <div class="ta-chat-header-info">
                <h2>{{ $user->firstname }} {{ $user->lastname }}</h2>
                <p>ZIP {{ $user->zipcode }} · {{ $user->email }}</p>
            </div>
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('toapp.agent.users.show', $user) }}">View profile</a>
        </div>

        <div id="chatMessages" class="ta-chat-messages">
            @forelse($messages as $message)
                @php $isOwn = $message->sender_type === 'agent'; @endphp
                <div class="ta-chat-msg {{ $isOwn ? 'ta-chat-msg--own' : 'ta-chat-msg--peer' }}" data-id="{{ $message->id }}">
                    <div class="ta-chat-avatar">{{ $isOwn ? $agentInitials : $clientInitials }}</div>
                    <div class="ta-chat-bubble">
                        @if($message->message)
                            <p class="ta-chat-text">{!! nl2br(e($message->message)) !!}</p>
                        @endif
                        @include('partials.agent_chat_attachments', ['message' => $message, 'variant' => 'ta'])
                        <time class="ta-chat-time">{{ $message->created_at->format('M d, H:i') }}</time>
                    </div>
                </div>
            @empty
                <div class="ta-chat-empty">No messages yet. Say hello to your client.</div>
            @endforelse
        </div>

        <div class="ta-chat-composer-wrap">
            <div id="chatAttachmentPreview" class="chat-attachment-preview ta"></div>

            <form id="chatForm" class="ta-chat-composer" method="POST" action="{{ route('toapp.agent.chat.send', $user) }}" enctype="multipart/form-data">
                @csrf
                <div class="ta-chat-composer-toolbar">
                    @include('partials.agent_chat_composer_attach', ['variant' => 'ta'])
                    <textarea name="message" rows="1" placeholder="Type a message…"></textarea>
                    <button class="ta-chat-send-btn" type="submit" aria-label="Send message">
                        <i class="ti ti-send"></i>
                    </button>
                </div>
                <p class="ta-chat-composer-hint">Photo or file · up to 5 files, 10 MB each</p>
            </form>
        </div>
    </div>
</section>

@include('partials.agent_chat_realtime', [
    'theme' => 'agent',
    'boxId' => 'chatMessages',
    'formId' => 'chatForm',
    'pollUrl' => route('toapp.agent.chat.messages', $user),
    'pusherAuthEndpoint' => $pusherAuthEndpoint,
    'conversationId' => $conversation->id,
    'lastMessageId' => optional($messages->last())->id ?? 0,
    'ownSender' => 'agent',
    'ownLabel' => $agentInitials,
    'peerLabel' => $clientInitials,
])
@endsection

@push('script-lib')
    @if(filled(config('broadcasting.connections.pusher.key')))
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
    @endif
@endpush

@push('scripts')
<script>
(function () {
    const textarea = document.querySelector('#chatForm textarea');
    if (!textarea) return;

    textarea.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });
})();
</script>
@endpush
