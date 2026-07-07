@if($message->attachments->isNotEmpty())
    <div class="chat-attachments {{ $variant ?? 'ci' }}">
        @foreach($message->attachments as $attachment)
            @if($attachment->isImage())
                <a class="chat-attachment-image" href="{{ $attachment->url() }}" target="_blank" rel="noopener">
                    <img src="{{ $attachment->url() }}" alt="{{ $attachment->original_name }}" loading="lazy">
                </a>
            @else
                <a class="chat-attachment-file" href="{{ $attachment->url() }}" target="_blank" rel="noopener" download>
                    <span class="chat-attachment-icon" aria-hidden="true">📄</span>
                    <span class="chat-attachment-name">{{ $attachment->original_name }}</span>
                </a>
            @endif
        @endforeach
    </div>
@endif
