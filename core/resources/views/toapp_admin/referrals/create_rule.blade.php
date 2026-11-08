@extends('toapp_admin.layouts.app')

@section('content')
<div class="portal-page-actions">
    <a href="{{ route('toapp.admin.referrals.index') }}" class="btn btn-ghost-secondary btn-sm"><i class="ti ti-arrow-left me-1"></i> @lang('Back to referrals')</a>
</div>

<form method="POST" action="{{ route('toapp.admin.referrals.rules.store') }}">
    @csrf
    <div class="card">
        <div class="card-header"><h3 class="card-title">@lang('New referral rule')</h3></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">@lang('Package')</label>
                    <select class="form-select" name="plan_id">
                        <option value="">@lang('Global fallback')</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" @selected(old('plan_id') == $plan->id)>{{ $plan->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label required">@lang('Level')</label>
                    <input class="form-control" type="number" name="level" min="1" max="20" value="{{ old('level', 1) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label required">@lang('Percent')</label>
                    <input class="form-control" type="number" name="percent" min="0" max="100" step="0.01" value="{{ old('percent') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">@lang('Status')</label>
                    <select class="form-select" name="status">
                        <option value="1" @selected(old('status', '1') == '1')>@lang('Enabled')</option>
                        <option value="0" @selected(old('status') === '0')>@lang('Disabled')</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer"><button class="btn btn-primary" type="submit">@lang('Save rule')</button></div>
    </div>
</form>
@endsection
