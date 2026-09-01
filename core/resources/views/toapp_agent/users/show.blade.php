@extends('toapp_agent.layouts.app')

@section('content')
@php
    $initials = collect([$user->firstname ?? '', $user->lastname ?? ''])->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('') ?: 'CL';
    $approvalStatus = $user->approval_status ?? 'pending';
    $approvalBadge = match ($approvalStatus) {
        'approved' => 'bg-success-lt',
        'rejected' => 'bg-danger-lt',
        default => 'bg-warning-lt',
    };
@endphp

<div class="row row-deck row-cards mb-4">
    <div class="col-sm-4">
        <div class="card portal-stat-card">
            <div class="card-body">
                <div class="subheader">Products</div>
                <div class="h2 mb-1">{{ $investments->count() }}</div>
                <div class="text-secondary">On record for this client</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card portal-stat-card">
            <div class="card-body">
                <div class="subheader">US ZIP code</div>
                <div class="h2 mb-1">{{ $user->zipcode ?: '—' }}</div>
                <div class="text-secondary">Service territory</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card portal-stat-card">
            <div class="card-body">
                <div class="subheader">Approval</div>
                <div class="h2 mb-2"><span class="badge {{ $approvalBadge }}">{{ ucfirst($approvalStatus) }}</span></div>
                <div class="text-secondary">Member onboarding status</div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <span class="portal-client-avatar">{{ $initials }}</span>
            <div>
                <div class="text-secondary small mb-1">Client profile</div>
                <h3 class="card-title mb-0">{{ $user->firstname }} {{ $user->lastname }}</h3>
                <div class="text-secondary">{{ '@' . $user->username }}</div>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('toapp.agent.users.index') }}">Back to clients</a>
            <a class="btn btn-primary" href="{{ route('toapp.agent.chat.show', $user) }}"><i class="ti ti-messages me-1"></i> Open chat</a>
        </div>
    </div>
    <div class="list-group list-group-flush portal-detail-list">
        @foreach([
            ['Email', $user->email],
            ['Phone', $user->mobile ?: '—'],
            ['US ZIP code', $user->zipcode ?: '—'],
            ['Agent license used', $user->agent_license_no ?: '—'],
            ['Approval', ucfirst($approvalStatus)],
            ['Referral code', $user->refno ?: '—'],
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
        <div class="card-subtitle">Products held</div>
        <h3 class="card-title">Active &amp; past packages</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Plan</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Started</th>
                </tr>
            </thead>
            <tbody>
                @forelse($investments as $investment)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ optional($investment->plan)->name ?? '—' }}</div>
                        </td>
                        <td>{{ showAmount($investment->amount) }}</td>
                        <td>{!! $investment->statusBadge !!}</td>
                        <td class="text-secondary">{{ optional($investment->created_at)->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-secondary py-4">No products on record.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
