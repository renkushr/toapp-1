@extends('toapp_agent.layouts.app')

@section('content')
@php
    $client = $application->user;
    $initials = collect([$client->firstname ?? '', $client->lastname ?? ''])->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('') ?: 'CL';
    $isConfirmed = $application->status === \App\Models\InsuranceApplication::STATUS_CONFIRMED;
    $livingLabel = ($application->plan?->category === 'Term Life') ? 'Living Benefits' : 'Long term care';
@endphp

<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <span class="portal-client-avatar">{{ $initials }}</span>
            <div>
                <div class="text-secondary small mb-1">Application {{ $application->reference }}</div>
                <h3 class="card-title mb-0">{{ $client?->firstname }} {{ $client?->lastname }}</h3>
                <div class="text-secondary">{{ $client?->email }}</div>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('toapp.agent.applications.index') }}">Back to applications</a>
            @if($client)
                <a class="btn btn-primary" href="{{ route('toapp.agent.chat.show', $client) }}"><i class="ti ti-messages me-1"></i> Open chat</a>
            @endif
        </div>
    </div>
    <div class="list-group list-group-flush portal-detail-list">
        @foreach([
            ['ZIP code', $application->zipcode ?: '—'],
            ['Product', $application->plan?->name ?? '—'],
            ['Coverage amount', $application->coverage_amount ? '$' . number_format((int) $application->coverage_amount) : '—'],
            ['Coverage length', $application->coverage_length ? $application->coverage_length . ' years' : '—'],
            [$livingLabel, $application->living_benefits ? 'Yes' : 'No'],
            ['Annual quote', '$' . number_format((float) ($application->quoted_annual ?? 0), 2) . '/yr'],
            ['Monthly quote', $application->quoted_monthly ? '$' . number_format((float) $application->quoted_monthly, 2) . '/mo' : '—'],
            ['Status', $application->statusLabel()],
        ] as [$label, $value])
            <div class="list-group-item portal-detail-row">
                <span class="text-secondary">{{ $label }}</span>
                <strong>{{ $value }}</strong>
            </div>
        @endforeach
    </div>
</div>

<div class="card">
    <div class="card-header portal-card-header-stacked">
        <div class="card-subtitle">Offline payment</div>
        <h3 class="card-title mb-0">Confirm after payment</h3>
    </div>
    <div class="card-body">
        @if($isConfirmed)
            <div class="alert alert-success mb-0">
                Policy confirmed on {{ optional($application->payment_confirmed_at)->format('M d, Y H:i') }}.
            </div>
        @else
            <p class="text-secondary mb-4">
                The client completes payment outside the app. Mark the application when payment is expected, then confirm once the policy is sold.
            </p>

            <div class="portal-action-block mb-4">
                <div class="portal-action-block-head">
                    <h4 class="mb-1">Step 1 — Awaiting payment</h4>
                    <p class="text-secondary mb-0">Use this when the client has been instructed to pay but confirmation is not done yet.</p>
                </div>
                <form method="POST" action="{{ route('toapp.agent.applications.payment_pending', $application) }}" class="mt-3">
                    @csrf
                    <button class="btn btn-outline-primary" type="submit">Mark awaiting payment</button>
                </form>
            </div>

            <div class="portal-action-block">
                <div class="portal-action-block-head">
                    <h4 class="mb-1">Step 2 — Confirm policy sold</h4>
                    <p class="text-secondary mb-0">Record notes and confirm after offline payment is received.</p>
                </div>
                <form method="POST" action="{{ route('toapp.agent.applications.confirm', $application) }}" class="mt-3">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="payment-notes">Notes</label>
                        <textarea class="form-control" id="payment-notes" name="notes" rows="3" placeholder="Payment reference, amount received, or follow-up details"></textarea>
                    </div>
                    <button class="btn btn-primary" type="submit">Confirm policy sold</button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
