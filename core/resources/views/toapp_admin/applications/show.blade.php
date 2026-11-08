@extends('toapp_admin.layouts.app')

@section('content')
@php
    $client = $application->user;
    $isConfirmed = $application->status === \App\Models\InsuranceApplication::STATUS_CONFIRMED;
    $livingLabel = ($application->plan?->category === 'Term Life') ? __('Living Benefits') : __('Long term care');
    $plansByCategory = ($plans ?? collect())->groupBy('category');
@endphp

<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <div class="text-secondary small">@lang('Application') {{ $application->reference }}</div>
            <h3 class="card-title mb-0">{{ $client?->firstname }} {{ $client?->lastname }}</h3>
            <div class="text-secondary">{{ $client?->email }}</div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('toapp.admin.applications.index') }}">@lang('Back to applications')</a>
            @if($client && auth('admin')->user()?->canAccess('investments'))
                <form method="POST" action="{{ route('toapp.admin.applications.store', $client) }}">
                    @csrf
                    <button class="btn btn-primary" type="submit"><i class="ti ti-plus me-1"></i> @lang('Add package')</button>
                </form>
            @endif
        </div>
    </div>
    <div class="list-group list-group-flush">
        @foreach([
            [__('Portal'), $application->portal?->name ?: '—'],
            [__('ZIP code'), $application->zipcode ?: '—'],
            [__('Product'), $application->plan?->name ?: '—'],
            [__('Quoted'), '$' . number_format((float) ($application->quoted_annual ?? 0), 2) . ' / yr · $' . number_format((float) ($application->quoted_monthly ?? 0), 2) . ' / mo'],
            [__('Agent'), $application->agent?->name ?: '—'],
            [__('Status'), __($application->statusLabel())],
        ] as [$label, $value])
            <div class="list-group-item d-flex justify-content-between gap-3">
                <span class="text-secondary">{{ $label }}</span>
                <strong class="text-end">{{ $value }}</strong>
            </div>
        @endforeach
    </div>
</div>

@unless($isConfirmed)
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title mb-0">@lang('Edit product, quote & conditions')</h3>
    </div>
    <div class="card-body">
        <p class="text-secondary mb-4">
            @lang("Change the customer's product and underwriting conditions. You can load a suggested plan price, then override it.")
        </p>

        <form method="POST" action="{{ route('toapp.admin.applications.quote', $application) }}" id="admin-quote-form">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-12">
                    <h4 class="mb-0">@lang('Product')</h4>
                </div>
                <div class="col-12 col-lg-8">
                    <label class="form-label" for="plan-id">@lang('Product')</label>
                    <select class="form-select" id="plan-id" name="plan_id" required>
                        <option value="">@lang('Select a product…')</option>
                        @foreach($plansByCategory as $category => $categoryPlans)
                            <optgroup label="{{ $category }}">
                                @foreach($categoryPlans as $plan)
                                    @php $range = max(1, min(8, (int) ($application->price_range ?? 1))); @endphp
                                    <option
                                        value="{{ $plan->id }}"
                                        data-category="{{ $plan->category }}"
                                        @selected((int) old('plan_id', $application->plan_id) === (int) $plan->id)
                                    >
                                        {{ $plan->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('plan_id')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-6 col-lg-2">
                    <label class="form-label" for="price-range">@lang('Price tier')</label>
                    <select class="form-select" id="price-range" name="price_range">
                        @for($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" @selected((int) old('price_range', $application->price_range ?? 1) === $i)>@lang('Tier') {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-6 col-lg-2">
                    <label class="form-label" for="pay-type">@lang('Pay type')</label>
                    <select class="form-select" id="pay-type" name="pay_type" required>
                        <option value="year" @selected(old('pay_type', $application->pay_type ?: 'year') === 'year')>@lang('Annual')</option>
                        <option value="month" @selected(old('pay_type', $application->pay_type) === 'month')>@lang('Monthly')</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12">
                    <h4 class="mb-0">@lang('Customer conditions')</h4>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label" for="sex">@lang('Sex')</label>
                    <select class="form-select" id="sex" name="sex" required>
                        <option value="1" @selected((int) old('sex', $application->sex ?: $client?->sex) === 1)>@lang('Male')</option>
                        <option value="2" @selected((int) old('sex', $application->sex ?: $client?->sex) === 2)>@lang('Female')</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label" for="smoking">@lang('Smoking')</label>
                    <select class="form-select" id="smoking" name="smoking" required>
                        <option value="0" @selected((int) old('smoking', $application->smoking) === 0)>@lang('No')</option>
                        <option value="1" @selected((int) old('smoking', $application->smoking) === 1)>@lang('Yes')</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label" for="health">@lang('Health')</label>
                    <select class="form-select" id="health" name="health" required>
                        <option value="1" @selected((int) old('health', $application->health ?? 1) === 1)>@lang('Good')</option>
                        <option value="2" @selected((int) old('health', $application->health) === 2)>@lang('Fair / average')</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label" for="chronic-disease">@lang('Chronic condition')</label>
                    <select class="form-select" id="chronic-disease" name="chronic_disease" required>
                        <option value="0" @selected(!(bool) old('chronic_disease', $application->chronic_disease))>@lang('No')</option>
                        <option value="1" @selected((bool) old('chronic_disease', $application->chronic_disease))>@lang('Yes')</option>
                    </select>
                </div>
                <div class="col-6 col-md-4">
                    <label class="form-label" for="coverage-amount">@lang('Coverage amount')</label>
                    <select class="form-select" id="coverage-amount" name="coverage_amount" required>
                        @foreach($coverageAmounts as $amount)
                            <option value="{{ $amount }}" @selected((int) old('coverage_amount', $application->coverage_amount) === (int) $amount)>
                                ${{ number_format($amount) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-4" id="coverage-length-wrap">
                    <label class="form-label" for="coverage-length">@lang('Coverage length')</label>
                    <select class="form-select" id="coverage-length" name="coverage_length">
                        @foreach($coverageLengths as $years)
                            <option value="{{ $years }}" @selected((int) old('coverage_length', $application->coverage_length ?: 20) === (int) $years)>
                                {{ $years }} @lang('years')
                            </option>
                        @endforeach
                    </select>
                    <small class="form-hint">@lang('Shown for Term Life only.')</small>
                </div>
                <div class="col-6 col-md-4">
                    <label class="form-label" for="living-benefits" id="living-benefits-label">{{ $livingLabel }}</label>
                    <select class="form-select" id="living-benefits" name="living_benefits" required>
                        <option value="0" @selected(!(bool) old('living_benefits', $application->living_benefits))>@lang('No')</option>
                        <option value="1" @selected((bool) old('living_benefits', $application->living_benefits))>@lang('Yes')</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-0">@lang('Pricing')</h4>
                        <p class="text-secondary small mb-0">@lang('Enter the final quote shown to the customer.')</p>
                    </div>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="fill-plan-price">
                        @lang('Load suggested plan price')
                    </button>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="quoted-monthly">@lang('Monthly quote ($)')</label>
                    <input type="number" step="0.01" min="0" class="form-control" id="quoted-monthly" name="quoted_monthly" value="{{ old('quoted_monthly', $application->quoted_monthly ?? 0) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="quoted-annual">@lang('Annual quote ($)')</label>
                    <input type="number" step="0.01" min="0" class="form-control" id="quoted-annual" name="quoted_annual" value="{{ old('quoted_annual', $application->quoted_annual ?? 0) }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label" for="admin-notes">@lang('Notes (optional)')</label>
                    <textarea class="form-control" id="admin-notes" name="admin_notes" rows="2">{{ old('admin_notes', $application->admin_notes) }}</textarea>
                </div>
            </div>

            <button class="btn btn-primary" type="submit">@lang('Save product & quote')</button>
        </form>
    </div>
</div>
@endunless

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">@lang('Confirm / Reject')</h3>
    </div>
    <div class="card-body">
        @if($isConfirmed)
            <div class="alert alert-success mb-0">
                @lang('Confirmed') {{ optional($application->payment_confirmed_at)->format('M d, Y H:i') }}
            </div>
        @else
            <form method="POST" action="{{ route('toapp.admin.applications.confirm', $application) }}" class="mb-4">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="confirm-notes">@lang('Admin notes')</label>
                    <textarea class="form-control" id="confirm-notes" name="notes" rows="2">{{ $application->admin_notes }}</textarea>
                </div>
                <button class="btn btn-primary" type="submit">@lang('Confirm policy sold')</button>
            </form>
            <form method="POST" action="{{ route('toapp.admin.applications.reject', $application) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="reject-reason">@lang('Reject reason')</label>
                    <textarea class="form-control" id="reject-reason" name="reason" rows="2" required></textarea>
                </div>
                <button class="btn btn-outline-danger" type="submit">@lang('Reject')</button>
            </form>
        @endif
    </div>
</div>
@endsection

@unless($isConfirmed)
@push('scripts')
@php
    $adminApplicationI18n = [
        'livingBenefits' => __('Living Benefits'),
        'longTermCare' => __('Long term care'),
    ];
@endphp
<script>
(function () {
    const i18n = @json($adminApplicationI18n);
    const planSelect = document.getElementById('plan-id');
    const lengthWrap = document.getElementById('coverage-length-wrap');
    const lengthSelect = document.getElementById('coverage-length');
    const livingLabel = document.getElementById('living-benefits-label');
    const monthlyInput = document.getElementById('quoted-monthly');
    const annualInput = document.getElementById('quoted-annual');
    const fillBtn = document.getElementById('fill-plan-price');
    const planPrices = @json($planPrices ?? []);
    const priceRange = document.getElementById('price-range');

    function selectedPlanMeta() {
        const id = planSelect?.value;
        return id && planPrices[id] ? planPrices[id] : null;
    }

    function syncCoverageLength() {
        const meta = selectedPlanMeta();
        const isTerm = meta ? meta.category === 'Term Life' : true;
        if (lengthWrap) lengthWrap.style.display = isTerm ? '' : 'none';
        if (lengthSelect) {
            lengthSelect.disabled = !isTerm;
            lengthSelect.required = isTerm;
        }
        if (livingLabel) {
            livingLabel.textContent = isTerm ? i18n.livingBenefits : i18n.longTermCare;
        }
    }

    function fillSuggestedPrice() {
        const meta = selectedPlanMeta();
        if (!meta || !monthlyInput || !annualInput) return;
        const tier = String(priceRange?.value || '1');
        const prices = meta.tiers[tier] || meta.tiers['1'] || { monthly: 0, annual: 0 };
        monthlyInput.value = Number(prices.monthly || 0).toFixed(2);
        annualInput.value = Number(prices.annual || 0).toFixed(2);
    }

    planSelect?.addEventListener('change', syncCoverageLength);
    fillBtn?.addEventListener('click', fillSuggestedPrice);
    syncCoverageLength();
})();
</script>
@endpush
@endunless
