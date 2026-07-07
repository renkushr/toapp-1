@extends('toapp_admin.layouts.app')

@section('content')
@php $currency = $gateway->singleCurrency; @endphp
<div class="portal-page-actions">
    <a href="{{ route('toapp.admin.methods.index') }}" class="btn btn-ghost-secondary btn-sm"><i class="ti ti-arrow-left me-1"></i> Back to methods</a>
</div>

<div class="row">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('toapp.admin.methods.deposit.update', $gateway) }}">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header"><h3 class="card-title">Deposit gateway · {{ $gateway->name }}</h3></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label required">Display name</label>
                        <input class="form-control" name="name" value="{{ old('name', $gateway->name) }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Currency</label><input class="form-control" name="currency" value="{{ old('currency', $currency->currency ?? '') }}"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Rate</label><input class="form-control" type="number" step="0.00000001" name="rate" value="{{ old('rate', $currency->rate ?? 1) }}"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Min amount</label><input class="form-control" type="number" step="0.00000001" name="min_amount" value="{{ old('min_amount', $currency->min_amount ?? 0) }}"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Max amount</label><input class="form-control" type="number" step="0.00000001" name="max_amount" value="{{ old('max_amount', $currency->max_amount ?? 0) }}"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Fixed charge</label><input class="form-control" type="number" step="0.00000001" name="fixed_charge" value="{{ old('fixed_charge', $currency->fixed_charge ?? 0) }}"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Percent charge</label><input class="form-control" type="number" step="0.01" name="percent_charge" value="{{ old('percent_charge', $currency->percent_charge ?? 0) }}"></div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Instructions</label>
                        <textarea class="form-control" name="description" rows="4">{{ old('description', $gateway->description) }}</textarea>
                    </div>
                </div>
                <div class="card-footer"><button class="btn btn-primary" type="submit">Save gateway</button></div>
            </div>
        </form>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Status</h3></div>
            <div class="card-body">
                <p class="text-secondary">Code <strong>{{ $gateway->code }}</strong> · Alias <strong>{{ $gateway->alias }}</strong></p>
                <span class="badge {{ $gateway->status ? 'bg-success-lt' : 'bg-danger-lt' }}">{{ $gateway->status ? 'Enabled' : 'Disabled' }}</span>
            </div>
            <div class="card-footer">
                <form method="POST" action="{{ route('toapp.admin.methods.deposit.status', $gateway) }}">
                    @csrf
                    <button class="btn w-100" type="submit">{{ $gateway->status ? 'Disable gateway' : 'Enable gateway' }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
