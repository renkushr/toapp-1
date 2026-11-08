@extends($activeTemplate . 'layouts.master')

@section('content')
<div class="app-page">
    <div class="app-container">
        <div class="app-topbar">
            <a class="app-icon-btn" href="{{ route('user.call') }}" aria-label="@lang('Back to contact support')">
                <i class="las la-arrow-left"></i>
            </a>
            <a class="app-brand" href="{{ route('user.home') }}">
                <img src="{{ siteLogo() }}" alt="To-App">
                <span>@lang('Chat')</span>
            </a>
            <a class="app-icon-btn" href="{{ route('ticket.open') }}" aria-label="@lang('New chat')">
                <i class="las la-plus"></i>
            </a>
        </div>

        <section class="app-hero">
            <span class="app-eyebrow"><i class="las la-comments"></i> @lang('Support chat')</span>
            <h1>@lang('Message our team in one place.')</h1>
            <p>@lang('Start a chat when you need help, then follow replies here without losing the conversation history.')</p>
        </section>

        <div class="app-section-title">
            <div>
                <h2>@lang('My chats')</h2>
                <p>@lang('Status, priority, and last reply are shown for every conversation.')</p>
            </div>
            <a href="{{ route('ticket.open') }}" class="app-btn app-btn-primary">
                <i class="las la-plus"></i>
                @lang('New chat')
            </a>
        </div>

        <div class="app-list-card">
            @forelse($supports as $support)
                <a class="app-list-item" href="{{ route('ticket.view', $support->ticket) }}">
                    <span class="app-list-icon"><i class="las la-comments"></i></span>
                    <div class="app-list-body">
                        <strong>[@lang('Chat')#{{ $support->ticket }}] {{ __($support->subject) }}</strong>
                        <span>
                            @php echo $support->statusBadge; @endphp
                            ·
                            @if($support->priority == Status::PRIORITY_LOW)
                                @lang('Low priority')
                            @elseif($support->priority == Status::PRIORITY_MEDIUM)
                                @lang('Medium priority')
                            @elseif($support->priority == Status::PRIORITY_HIGH)
                                @lang('High priority')
                            @endif
                            · @lang('Last reply') {{ diffForHumans($support->last_reply) }}
                        </span>
                    </div>
                    <i class="las la-angle-right"></i>
                </a>
            @empty
                <div class="app-empty-state">
                    <i class="las la-comments"></i>
                    <p>{{ __($emptyMessage ?? 'No chats yet.') }}</p>
                    <a href="{{ route('ticket.open') }}" class="app-btn app-btn-primary">
                        <i class="las la-plus"></i>
                        @lang('Start your first chat')
                    </a>
                </div>
            @endforelse
        </div>

        @if($supports->hasPages())
            <div class="mt-3">
                {{ paginateLinks($supports) }}
            </div>
        @endif
    </div>
</div>
@endsection
