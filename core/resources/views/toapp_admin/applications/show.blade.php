@extends('toapp_admin.layouts.app')

@section('content')
<section class="ta-grid ta-grid-main">
    <article class="ta-panel">
        <div class="ta-panel-head"><div><span class="ta-kicker">{{ $application->reference }}</span><h2>Application detail</h2></div></div>
        <div class="ta-detail-list">
            <div><span>Portal</span><strong>{{ $application->portal?->name }}</strong></div>
            <div><span>Customer</span><strong>{{ $application->user?->firstname }} {{ $application->user?->lastname }} ({{ $application->user?->email }})</strong></div>
            <div><span>ZIP</span><strong>{{ $application->zipcode }}</strong></div>
            <div><span>Product</span><strong>{{ $application->plan?->name ?: '—' }}</strong></div>
            <div><span>Quoted</span><strong>${{ number_format($application->quoted_annual ?? 0, 2) }} / yr · ${{ number_format($application->quoted_monthly ?? 0, 2) }} / mo</strong></div>
            <div><span>Agent</span><strong>{{ $application->agent?->name ?: '—' }}</strong></div>
            <div><span>Status</span><strong>{{ $application->statusLabel() }}</strong></div>
        </div>
    </article>
    <article class="ta-panel">
        <div class="ta-panel-head"><div><span class="ta-kicker">After external payment</span><h2>Confirm policy</h2></div></div>
        @if($application->status !== \App\Models\InsuranceApplication::STATUS_CONFIRMED)
            <form method="POST" action="{{ route('toapp.admin.applications.confirm', $application) }}" class="ta-form-stack mb-3">
                @csrf
                <label class="ta-field"><span>Admin notes</span><textarea name="notes" rows="2">{{ $application->admin_notes }}</textarea></label>
                <button class="ta-primary-btn" type="submit">Confirm policy sold</button>
            </form>
            <form method="POST" action="{{ route('toapp.admin.applications.reject', $application) }}" class="ta-form-stack">
                @csrf
                <label class="ta-field"><span>Reject reason</span><textarea name="reason" rows="2" required></textarea></label>
                <button class="ta-danger-btn" type="submit">Reject</button>
            </form>
        @else
            <p class="ta-alert ta-alert-success">Confirmed {{ optional($application->payment_confirmed_at)->format('M d, Y H:i') }}</p>
        @endif
    </article>
</section>
@endsection
