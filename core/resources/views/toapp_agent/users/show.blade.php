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
                <div class="subheader">@lang('Products')</div>
                <div class="h2 mb-1">{{ $investments->count() }}</div>
                <div class="text-secondary">@lang('On record for this client')</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card portal-stat-card">
            <div class="card-body">
                <div class="subheader">@lang('US ZIP code')</div>
                <div class="h2 mb-1">{{ $user->zipcode ?: '—' }}</div>
                <div class="text-secondary">@lang('Service territory')</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card portal-stat-card">
            <div class="card-body">
                <div class="subheader">@lang('Approval')</div>
                <div class="h2 mb-2"><span class="badge {{ $approvalBadge }}">{{ __(ucfirst($approvalStatus)) }}</span></div>
                <div class="text-secondary">@lang('Member onboarding status')</div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <span class="portal-client-avatar">{{ $initials }}</span>
            <div>
                <div class="text-secondary small mb-1">@lang('Client profile')</div>
                <h3 class="card-title mb-0">{{ $user->firstname }} {{ $user->lastname }}</h3>
                <div class="text-secondary">{{ '@' . $user->username }}</div>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('toapp.agent.users.index') }}">@lang('Back to clients')</a>
            <a class="btn btn-primary" href="{{ route('toapp.agent.chat.show', $user) }}"><i class="ti ti-messages me-1"></i> @lang('Open chat')</a>
        </div>
    </div>
    <div class="list-group list-group-flush portal-detail-list">
        @foreach([
            [__('Email'), $user->email],
            [__('Phone'), $user->mobile ?: '—'],
            [__('US ZIP code'), $user->zipcode ?: '—'],
            [__('Agent license used'), $user->agent_license_no ?: '—'],
            [__('Approval'), __(ucfirst($approvalStatus))],
            [__('Referral code'), $user->refno ?: '—'],
        ] as [$label, $value])
            <div class="list-group-item portal-detail-row">
                <span class="text-secondary">{{ $label }}</span>
                <strong>{{ $value }}</strong>
            </div>
        @endforeach
    </div>
</div>

<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <div class="card-subtitle">@lang('Insurance packages')</div>
            <h3 class="card-title mb-0">@lang('Applications for this client')</h3>
        </div>
        <form method="POST" action="{{ route('toapp.agent.applications.store', $user) }}">
            @csrf
            <button class="btn btn-primary" type="submit"><i class="ti ti-plus me-1"></i> @lang('Add package')</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>@lang('Reference')</th>
                    <th>@lang('Product')</th>
                    <th>@lang('Quoted')</th>
                    <th>@lang('Status')</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $application)
                    <tr>
                        <td class="fw-bold">{{ $application->reference }}</td>
                        <td>{{ $application->plan?->name ?? '—' }}</td>
                        <td class="text-secondary">${{ number_format((float) ($application->quoted_annual ?? 0), 2) }}/yr</td>
                        <td>{{ __($application->statusLabel()) }}</td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('toapp.agent.applications.show', $application) }}">@lang('Edit')</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">@lang('No applications yet.')</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header portal-card-header-stacked">
        <div class="card-subtitle">@lang('Products held')</div>
        <h3 class="card-title">@lang('Active & past packages')</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>@lang('Plan')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Started')</th>
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
                        <td colspan="4" class="text-center text-secondary py-4">@lang('No products on record.')</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
