@extends('toapp_admin.layouts.app')

@section('content')
<div class="portal-page-actions">
    <a href="{{ route('toapp.admin.methods.index') }}" class="btn btn-ghost-secondary btn-sm"><i class="ti ti-arrow-left me-1"></i> Back to methods</a>
</div>

<div class="row">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('toapp.admin.methods.withdraw.update', $method) }}">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header"><h3 class="card-title">Withdrawal method · {{ $method->name }}</h3></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label required">Display name</label>
                        <input class="form-control" name="name" value="{{ old('name', $method->name) }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label required">Currency</label><input class="form-control" name="currency" value="{{ old('currency', $method->currency) }}" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label required">Rate</label><input class="form-control" type="number" step="0.00000001" name="rate" value="{{ old('rate', $method->rate) }}" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label required">Min limit</label><input class="form-control" type="number" step="0.00000001" name="min_limit" value="{{ old('min_limit', $method->min_limit) }}" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label required">Max limit</label><input class="form-control" type="number" step="0.00000001" name="max_limit" value="{{ old('max_limit', $method->max_limit) }}" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label required">Fixed charge</label><input class="form-control" type="number" step="0.00000001" name="fixed_charge" value="{{ old('fixed_charge', $method->fixed_charge) }}" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label required">Percent charge</label><input class="form-control" type="number" step="0.01" name="percent_charge" value="{{ old('percent_charge', $method->percent_charge) }}" required></div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Instructions</label>
                        <textarea class="form-control" name="description" rows="4">{{ old('description', $method->description) }}</textarea>
                    </div>
                </div>
                <div class="card-footer"><button class="btn btn-primary" type="submit">Save method</button></div>
            </div>
        </form>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Status</h3></div>
            <div class="card-body">
                <span class="badge {{ $method->status ? 'bg-success-lt' : 'bg-danger-lt' }}">{{ $method->status ? 'Enabled' : 'Disabled' }}</span>
            </div>
            <div class="card-footer">
                <form method="POST" action="{{ route('toapp.admin.methods.withdraw.status', $method) }}">
                    @csrf
                    <button class="btn w-100" type="submit">{{ $method->status ? 'Disable method' : 'Enable method' }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
