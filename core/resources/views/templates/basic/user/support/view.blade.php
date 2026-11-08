@extends($activeTemplate . 'layouts.' . $layout)

@section('content')
<div class="app-page">
    <div class="app-container">
        <div class="app-topbar">
            <a class="app-icon-btn" href="{{ route('ticket.index') }}" aria-label="@lang('Back to chat')">
                <i class="las la-arrow-left"></i>
            </a>
            <a class="app-brand" href="{{ route('user.home') }}">
                <img src="{{ siteLogo() }}" alt="To-App">
                <span>@lang('Chat')</span>
            </a>
            @if ($myTicket->status != Status::TICKET_CLOSE && $myTicket->user)
                <button class="app-icon-btn confirmationBtn" type="button"
                    data-question="@lang('Are you sure to close this chat?')"
                    data-action="{{ route('ticket.close', $myTicket->id) }}"
                    aria-label="@lang('Close chat')">
                    <i class="las la-times"></i>
                </button>
            @else
                <span class="app-icon-btn" style="visibility:hidden"><i class="las la-comments"></i></span>
            @endif
        </div>

        <section class="app-hero">
            <span class="app-eyebrow"><i class="las la-comments"></i> @lang('Support chat') #{{ $myTicket->ticket }}</span>
            <h1>{{ __($myTicket->subject) }}</h1>
            <p>
                @php echo $myTicket->statusBadge; @endphp
                · @lang('Last reply') {{ diffForHumans($myTicket->last_reply) }}
            </p>
        </section>

        @if ($myTicket->status != Status::TICKET_CLOSE)
            <div class="app-card mb-3">
                <form id="reply_box" method="post" class="disableSubmission app-form-grid" action="{{ route('ticket.reply', $myTicket->id) }}" enctype="multipart/form-data">
                    @csrf
                    <label class="app-field app-field-full">
                        <span>@lang('Message')</span>
                        <textarea id="ticketMessage" name="message" rows="4" required placeholder="@lang('Write your reply...')">{{ old('message') }}</textarea>
                    </label>

                    <div class="app-field-full">
                        <button type="button" class="app-btn app-btn-ghost addAttachment">
                            <i class="las la-paperclip"></i> @lang('Add Attachment')
                        </button>
                        <p class="text-muted small mb-2 mt-2">
                            @lang('Max 5 files | Max size') {{ convertToReadableSize(ini_get('upload_max_filesize')) }}
                            · @lang('Allowed') .jpg, .jpeg, .png, .pdf, .doc, .docx
                        </p>
                        <div class="row fileUploadsContainer g-2"></div>
                    </div>

                    <button id="replayTicket" class="app-btn app-btn-primary w-100" type="submit">
                        <i class="las la-reply"></i> @lang('Reply')
                    </button>
                </form>
            </div>
        @else
            <div class="app-card mb-3">
                <p class="mb-0 text-muted">@lang('This chat is closed. Open a new chat if you still need help.')</p>
            </div>
        @endif

        <div id="reply_msgs" class="app-list-card">
            @forelse ($messages as $message)
                @include($activeTemplate . 'user.support.message', ['message' => $message])
            @empty
                <div class="app-empty-state">
                    <i class="las la-comments"></i>
                    <p>@lang('No messages yet.')</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div id="confirmationModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="question">@lang('Are you sure to close this chat?')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn-primary">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
    #reply_msgs .ticket-msg {
        padding: 14px 16px;
        border-bottom: 1px solid rgba(15, 23, 42, 0.08);
    }
    #reply_msgs .ticket-msg:last-child { border-bottom: 0; }
    #reply_msgs .ticket-msg.is-staff { background: rgba(255, 217, 103, 0.18); }
    #reply_msgs .ticket-msg strong { display: block; margin-bottom: 4px; }
    #reply_msgs .ticket-msg .meta { color: #64748b; font-size: 12px; margin-bottom: 8px; }
</style>
@endpush

@push('script')
@php
    $pusherKey = config('broadcasting.connections.pusher.key');
    $pusherCluster = config('broadcasting.connections.pusher.options.cluster');
    $broadcastEnabled = config('broadcasting.default') === 'pusher' && filled($pusherKey);
@endphp
@if($broadcastEnabled)
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
@endif
<script>
(function($) {
    "use strict";

    $(document).on('click', '.confirmationBtn', function() {
        var modal = $('#confirmationModal');
        var data = $(this).data();
        modal.find('.question').text(data.question);
        modal.find('form').attr('action', data.action);
        modal.modal('show');
    });

    var fileAdded = 0;
    $('.addAttachment').on('click', function() {
        fileAdded++;
        if (fileAdded >= 5) {
            $(this).prop('disabled', true);
        }
        $(".fileUploadsContainer").append(`
            <div class="col-12 removeFileInput">
                <div class="input-group">
                    <input type="file" name="attachments[]" class="form-control" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
                    <button type="button" class="btn btn-danger removeFile"><i class="las la-times"></i></button>
                </div>
            </div>
        `);
    });

    $(document).on('click', '.removeFile', function() {
        $('.addAttachment').prop('disabled', false);
        fileAdded = Math.max(0, fileAdded - 1);
        $(this).closest('.removeFileInput').remove();
    });

    @if($broadcastEnabled)
    if (typeof Echo !== 'undefined') {
        var echo = new Echo({
            broadcaster: 'pusher',
            key: @json($pusherKey),
            cluster: @json($pusherCluster),
            forceTLS: true
        });
        var ticketId = @json($myTicket->id);
        echo.channel('ticket.' + ticketId)
            .listen('.Message', function (data) {
                newMessage(data.message);
            })
            .listen('.DeleteMessage', function (data) {
                $('#reply_' + data.message.id).remove();
            });
    }
    @endif

    $('#reply_box').on('submit', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        var form = this;
        var formData = new FormData(form);
        var sendBtn = $('#replayTicket');
        var btnText = sendBtn.html();

        sendBtn.prop('disabled', true);
        sendBtn.html('<i class="las la-reply"></i> @lang("Replying...")');

        $.ajax({
            url: $(form).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            complete: function() {
                sendBtn.prop('disabled', false);
                sendBtn.html(btnText);
            },
            success: function(data) {
                if (data && data.message) {
                    newMessage(data.message);
                }
                $('#ticketMessage').val('');
                $(".fileUploadsContainer").html('');
                fileAdded = 0;
                $('.addAttachment').prop('disabled', false);
                if (data && data.notify && typeof notify === 'function') {
                    notify(data.notify[0], data.notify[1]);
                }
            },
            error: function(xhr) {
                var msg = 'Reply failed';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = Array.isArray(xhr.responseJSON.message.error)
                        ? xhr.responseJSON.message.error[0]
                        : (xhr.responseJSON.message.error || msg);
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors)[0][0];
                }
                if (typeof notify === 'function') {
                    notify('error', msg);
                } else {
                    alert(msg);
                }
            }
        });
    });

    function newMessage(message) {
        $.ajax({
            url: @json(route('ticket.message')),
            method: 'POST',
            data: { message: message, _token: @json(csrf_token()) },
            success: function(html) {
                if (html) {
                    $('#reply_msgs .app-empty-state').remove();
                    $('#reply_msgs').prepend(html);
                }
            }
        });
    }
})(jQuery);
</script>
@endpush
