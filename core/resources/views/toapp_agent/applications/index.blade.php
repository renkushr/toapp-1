@extends('toapp_agent.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <div class="text-secondary small text-uppercase fw-bold mb-1">@lang('Applications')</div>
        <h2 class="mb-0">@lang('Third-party quotes')</h2>
    </div>
</div>

<div class="card">
    <div class="list-group list-group-flush">
        @forelse($applications as $app)
            @php
                $client = $app->user;
                $canChat = (bool) $client;
            @endphp
            <div class="list-group-item">
                <div class="row align-items-center g-3">
                    <div class="col-12 col-md">
                        <div class="fw-bold">{{ $client?->firstname }} {{ $client?->lastname }}</div>
                        <div class="text-secondary small">
                            {{ $app->reference }}
                            · {{ $app->plan?->name ?: __('No product') }}
                            · @lang('ZIP') {{ $app->zipcode ?: '—' }}
                        </div>
                        <div class="mt-1">
                            <span class="badge bg-secondary-lt">{{ $app->statusLabel() }}</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-auto">
                        <div class="d-flex gap-2 flex-wrap agent-app-actions">
                            @if($canChat)
                                <a class="btn btn-primary flex-fill flex-md-grow-0" href="{{ route('toapp.agent.chat.show', $client) }}">
                                    <i class="ti ti-messages me-1"></i> @lang('Chat')
                                </a>
                            @endif
                            <a class="btn btn-outline-secondary flex-fill flex-md-grow-0" href="{{ route('toapp.agent.applications.show', $app) }}">
                                @lang('Open')
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="list-group-item text-secondary text-center py-5">
                @lang('No applications in your coverage areas yet.')
            </div>
        @endforelse
    </div>
</div>

<div class="mt-3">
    {{ $applications->links() }}
</div>
@endsection

@push('styles')
<style>
@media (max-width: 767.98px) {
    .agent-app-actions .btn {
        min-height: 44px;
        font-weight: 600;
    }
}
</style>
@endpush
