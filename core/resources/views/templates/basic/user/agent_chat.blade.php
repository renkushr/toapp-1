@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="app-page">
        <div class="app-container">
            <div class="app-topbar">
                <a class="app-icon-btn" href="{{ route('user.setting') }}" aria-label="@lang('Back')">
                    <i class="las la-arrow-left"></i>
                </a>
                <a class="app-brand" href="{{ route('user.home') }}">
                    <img src="{{ siteLogo() }}" alt="To-app">
                    <span>@lang('My Agent')</span>
                </a>
            </div>

            <section class="app-hero">
                <span class="app-eyebrow"><i class="las la-user-tie"></i> @lang('Private chat')</span>
                <h1>{{ $agent->name }}</h1>
                <p>@lang('License') {{ $agent->license_no }} · @lang('Your ZIP') {{ $user->zipcode }}</p>
            </section>

            @if(!filled(config('broadcasting.connections.pusher.key')))
                <div class="app-note">@lang('Realtime chat uses polling until Pusher is configured in .env')</div>
            @endif

            <div id="chatMessages" class="app-list-card" style="max-height:420px; overflow-y:auto; padding:16px;">
                @foreach($messages as $message)
                    <div class="app-list-item" data-id="{{ $message->id }}" style="flex-direction:column; align-items:{{ $message->sender_type === 'user' ? 'flex-end' : 'flex-start' }};">
                        <div style="background:{{ $message->sender_type === 'user' ? '#1268f3' : '#f1f5f9' }}; color:{{ $message->sender_type === 'user' ? '#fff' : '#0f172a' }}; padding:10px 14px; border-radius:12px; max-width:85%;">
                            @if($message->message)
                                {{ $message->message }}
                            @endif
                            @include('partials.agent_chat_attachments', ['message' => $message, 'variant' => 'app'])
                            <small style="display:block; opacity:.7; margin-top:4px;">{{ $message->created_at->format('M d, H:i') }}</small>
                        </div>
                    </div>
                @endforeach
            </div>

            <div id="chatAttachmentPreview" class="chat-attachment-preview app"></div>

            <form id="chatForm" action="{{ route('user.agent.chat.send') }}" method="POST" class="app-form-grid" style="margin-top:16px;" enctype="multipart/form-data">
                @csrf
                @include('partials.agent_chat_composer_attach', ['variant' => 'app', 'imageInputId' => 'chatImageInput', 'fileInputId' => 'chatFileInput'])
                <label class="app-field app-field-full">
                    <span>@lang('Message')</span>
                    <textarea name="message" rows="3" placeholder="@lang('Write to your agent or attach a photo/file...')"></textarea>
                </label>
                <button type="submit" class="app-btn app-btn-primary app-btn-full">@lang('Send')</button>
            </form>
        </div>
    </div>

    @include('partials.agent_chat_realtime', [
        'theme' => 'user',
        'boxId' => 'chatMessages',
        'formId' => 'chatForm',
        'pollUrl' => route('user.agent.chat.messages'),
        'pusherAuthEndpoint' => $pusherAuthEndpoint,
        'conversationId' => $conversation->id,
        'lastMessageId' => optional($messages->last())->id ?? 0,
        'ownSender' => 'user',
    ])
@endsection

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.34.0/dist/tabler-icons.min.css">
    <style>
        .chat-attach-actions--app { display: flex; gap: 8px; margin-bottom: 12px; }
        .chat-attach-actions--app .chat-attach-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 10px 14px; border-radius: 12px; border: 1px solid #dbe4ef;
            background: #f8fafc; color: #0f172a; font-weight: 600; cursor: pointer;
        }
        .chat-attach-actions--app .chat-attach-btn--image { color: #1268f3; border-color: #bfdbfe; background: #eff6ff; }
        .chat-attach-actions--app .chat-attach-input { display: none; }
        .chat-attachment-preview.app { display: flex; flex-wrap: wrap; gap: 8px; margin: 8px 0; }
        .chat-attachment-preview-item { width: 72px; height: 72px; border-radius: 10px; overflow: hidden; border: 1px solid #e2e8f0; }
        .chat-attachment-preview-item img { width: 100%; height: 100%; object-fit: cover; }
        .chat-attachment-chip { display: inline-flex; padding: 4px 10px; border-radius: 999px; background: #eef4ff; color: #1268f3; font-size: 12px; }
        .chat-attachments.app .chat-attachment-image img { max-width: 180px; border-radius: 10px; display: block; margin-top: 6px; }
        .chat-attachments.app .chat-attachment-file { display: inline-flex; gap: 6px; margin-top: 6px; color: inherit; }
    </style>
@endpush

@push('script-lib')
    @if(filled(config('broadcasting.connections.pusher.key')))
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
    @endif
@endpush
