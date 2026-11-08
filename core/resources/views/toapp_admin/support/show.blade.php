@extends('toapp_admin.layouts.app')

@section('content')
@php
    $ticketStatus = [0 => 'Open', 1 => 'Answered', 2 => 'Customer Reply', 3 => 'Closed'][$ticket->status] ?? null;
@endphp
<section class="ta-grid ta-grid-main">
    <article class="ta-panel">
        <div class="ta-panel-head">
            <div>
                <span class="ta-kicker">@lang('Conversation')</span>
                <h2>{{ $ticket->subject }}</h2>
            </div>
            <span class="ta-badge {{ $ticket->status == 3 ? 'muted' : 'success' }}">{{ $ticketStatus ? __($ticketStatus) : $ticket->status }}</span>
        </div>
        <div class="ta-thread">
            <div class="ta-message customer">
                <strong>{{ $ticket->name }}</strong>
                <small>{{ $ticket->email }} - {{ optional($ticket->created_at)->format('M d, Y H:i') }}</small>
                <p>{{ $ticket->subject }}</p>
            </div>
            @forelse($messages as $message)
                <div class="ta-message {{ $message->admin_id ? 'admin' : 'customer' }}">
                    <strong>{{ $message->admin_id ? __('Admin') : $ticket->name }}</strong>
                    <small>{{ optional($message->created_at)->format('M d, Y H:i') }}</small>
                    <p>{!! nl2br(e($message->message)) !!}</p>
                </div>
            @empty
                <div class="ta-empty">@lang('No replies yet.')</div>
            @endforelse
        </div>
    </article>

    <aside class="ta-panel">
        <div class="ta-panel-head">
            <div>
                <span class="ta-kicker">@lang('Reply')</span>
                <h2>@lang('Send response')</h2>
            </div>
        </div>
        @if($ticket->status != 3)
            <form class="ta-form-stack" method="POST" action="{{ route('toapp.admin.support.reply', $ticket) }}">
                @csrf
                <label class="ta-field"><span>@lang('Message')</span><textarea name="message" required placeholder="@lang('Write a clear, helpful answer...')">{{ old('message') }}</textarea></label>
                <button class="ta-primary-btn ta-fit-btn" type="submit"><i class="las la-paper-plane"></i> @lang('Send Reply')</button>
            </form>
            <form class="ta-form-stack" method="POST" action="{{ route('toapp.admin.support.close', $ticket) }}">
                @csrf
                <button class="ta-danger-btn" type="submit"><i class="las la-times-circle"></i> @lang('Close Ticket')</button>
            </form>
        @else
            <div class="ta-empty">@lang('This ticket is closed.')</div>
        @endif
    </aside>
</section>
@endsection
