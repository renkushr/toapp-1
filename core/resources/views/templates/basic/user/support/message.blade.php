@php
    $isStaff = (int) ($message->admin_id ?? 0) > 0;
    $author = $isStaff
        ? (optional($message->admin)->name ?? __('Staff'))
        : (optional($message->ticket)->name ?? __('You'));
@endphp
<div id="reply_{{ $message->id }}" class="ticket-msg {{ $isStaff ? 'is-staff' : 'is-user' }}">
    <strong>{{ $author }}@if($isStaff) <span class="text-muted">· @lang('Staff')</span>@endif</strong>
    <div class="meta">@lang('Posted on') {{ $message->created_at->format('l, d M Y @ H:i') }}</div>
    <p class="mb-0">{{ $message->message }}</p>
    @if ($message->attachments->count() > 0)
        <div class="mt-2">
            @foreach ($message->attachments as $k => $image)
                <a href="{{ route('ticket.download', encrypt($image->id)) }}" class="me-3">
                    <i class="las la-file"></i> @lang('Attachment') {{ ++$k }}
                </a>
            @endforeach
        </div>
    @endif
</div>
