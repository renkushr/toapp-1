@extends('toapp_admin.layouts.app')

@section('content')
@php
    $admin = auth('admin')->user();
    $statusLabel = match ((int) $investment->status) {
        0 => 'Pending',
        2 => 'Running',
        3 => 'Rejected',
        default => 'Completed',
    };
    $statusClass = match ((int) $investment->status) {
        0 => 'bg-warning-lt',
        2 => 'bg-success-lt',
        3 => 'bg-danger-lt',
        default => 'bg-secondary-lt',
    };
@endphp

<div class="portal-page-actions">
    <a href="{{ route('toapp.admin.reports.investments') }}" class="btn btn-ghost-secondary btn-sm"><i class="ti ti-arrow-left me-1"></i> Back to investments</a>
    <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
</div>

<div class="row row-cards">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Investment {{ $investment->trx ?? '#'.$investment->id }}</h3></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><div class="text-secondary">Member</div><div class="fw-bold">{{ optional($investment->user)->username }}</div><div class="text-secondary">{{ optional($investment->user)->email }}</div></div>
                    <div class="col-md-6"><div class="text-secondary">Package</div><div class="fw-bold">{{ optional($investment->plan)->name ?? '-' }}</div></div>
                    <div class="col-md-4"><div class="text-secondary">Amount</div><div class="fw-bold">{{ number_format((float) $investment->amount, 2) }}</div></div>
                    <div class="col-md-4"><div class="text-secondary">Interest</div><div class="fw-bold">{{ number_format((float) $investment->interest_amount, 2) }}</div></div>
                    <div class="col-md-4"><div class="text-secondary">Paid / return</div><div class="fw-bold">{{ $investment->total_paid }}/{{ $investment->total_return }}</div></div>
                    @if($investment->rejection_reason)
                        <div class="col-12"><div class="alert alert-danger mb-0">{{ $investment->rejection_reason }}</div></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        @if((int) $investment->status === 0 && $admin?->canAccess('investments'))
            <div class="card">
                <div class="card-header"><h3 class="card-title">Review decision</h3></div>
                <div class="card-body d-grid gap-3">
                    <form method="POST" action="{{ route('toapp.admin.reports.investments.approve', $investment) }}">
                        @csrf
                        <button class="btn btn-success w-100" type="submit">Approve investment</button>
                    </form>
                    <form method="POST" action="{{ route('toapp.admin.reports.investments.reject', $investment) }}">
                        @csrf
                        <label class="form-label required">Reject reason</label>
                        <textarea class="form-control mb-3" name="reason" rows="4" required placeholder="Why is this package purchase rejected?"></textarea>
                        <button class="btn btn-danger w-100" type="submit">Reject & refund</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
