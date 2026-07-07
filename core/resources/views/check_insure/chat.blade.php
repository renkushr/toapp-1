@extends('check_insure.layout')

@section('page_class', 'page-dark')
@section('hide_footer', true)

@push('styles')
    <link href="{{ asset('check-insure/css/chat.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.34.0/dist/tabler-icons.min.css">
@endpush

@section('content')
@php
    $agentInitials = collect(explode(' ', trim($agent->name)))->filter()->map(fn ($p) => strtoupper(substr($p, 0, 1)))->take(2)->implode('');
    $userInitials = collect([auth()->user()->firstname ?? '', auth()->user()->lastname ?? ''])->filter()->map(fn ($p) => strtoupper(substr($p, 0, 1)))->take(2)->implode('') ?: 'You';
@endphp
<div class="section p-0">
    <div class="container py-4">
        <div class="ci-chat-wrap">
            <div class="ci-chat-card">
                <div class="ci-chat-header">
                    <div class="ci-chat-avatar-lg">{{ $agentInitials ?: 'AG' }}</div>
                    <div class="ci-chat-header-info">
                        <h2>{{ $agent->name }}</h2>
                        <p>Licensed agent · {{ $agent->license_no }}</p>
                    </div>
                    <div class="ci-chat-ref">Ref {{ $application->reference }}</div>
                </div>

                <div id="chatMessages" class="ci-chat-messages">
                    @forelse($messages as $message)
                        @php $isOwn = $message->sender_type === 'user'; @endphp
                        <div class="ci-chat-msg {{ $isOwn ? 'ci-chat-msg--own' : 'ci-chat-msg--peer' }}" data-id="{{ $message->id }}">
                            <div class="ci-chat-avatar">{{ $isOwn ? $userInitials : ($agentInitials ?: 'AG') }}</div>
                            <div class="ci-chat-bubble">
                                @if($message->message)
                                    <p class="ci-chat-text">{{ $message->message }}</p>
                                @endif
                                @include('partials.agent_chat_attachments', ['message' => $message, 'variant' => 'ci'])
                                <time class="ci-chat-time">{{ $message->created_at->format('M d, H:i') }}</time>
                            </div>
                        </div>
                    @empty
                        <div class="ci-chat-empty">Say hello to your agent — ask about payment or policy details.</div>
                    @endforelse
                </div>

                <div id="chatAttachmentPreview" class="chat-attachment-preview ci"></div>

                <form id="chatForm" class="ci-chat-composer" method="POST" action="{{ route('checkinsure.chat.send', $application) }}" enctype="multipart/form-data">
                    @csrf
                    @include('partials.agent_chat_composer_attach', ['variant' => 'ci'])
                    <textarea name="message" rows="1" placeholder="Type a message, attach a photo or file…" aria-label="Message"></textarea>
                    <button class="ci-chat-send" type="submit" aria-label="Send message">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M22 2L11 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M22 2L15 22L11 13L2 9L22 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </form>
                <p class="ci-chat-hint mb-0">Up to 5 files, 10 MB each · jpg, png, pdf, doc, docx, txt</p>
            </div>
        </div>
    </div>
</div>

@include('partials.agent_chat_realtime', [
    'theme' => 'checkinsure',
    'boxId' => 'chatMessages',
    'formId' => 'chatForm',
    'pollUrl' => route('user.agent.chat.messages'),
    'pusherAuthEndpoint' => $pusherAuthEndpoint,
    'conversationId' => $conversation->id,
    'lastMessageId' => optional($messages->last())->id ?? 0,
    'ownSender' => 'user',
    'peerLabel' => $agentInitials ?: 'AG',
    'ownLabel' => $userInitials,
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
