@extends('toapp_agent.layouts.app')

@section('content')
@php
    $client = $application->user;
    $initials = collect([$client?->firstname ?? '', $client?->lastname ?? ''])->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('') ?: 'CL';
    $isConfirmed = $application->status === \App\Models\InsuranceApplication::STATUS_CONFIRMED;
    $livingLabel = ($application->plan?->category === 'Term Life') ? __('Living Benefits') : __('Long term care');
    $plansByCategory = ($plans ?? collect())->groupBy('category');
    $sexLabel = match ((int) ($application->sex ?? 0)) {
        1 => __('Male'),
        2 => __('Female'),
        default => '—',
    };
    $healthLabel = match ((int) ($application->health ?? 0)) {
        1 => __('Good'),
        2 => __('Fair / average'),
        default => '—',
    };
@endphp

<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <span class="portal-client-avatar">{{ $initials }}</span>
            <div>
                <div class="text-secondary small mb-1">@lang('Application') {{ $application->reference }}</div>
                <h3 class="card-title mb-0">{{ $client?->firstname }} {{ $client?->lastname }}</h3>
                <div class="text-secondary">{{ $client?->email }}</div>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2 w-100 w-sm-auto agent-app-detail-actions">
            <a class="btn btn-outline-secondary flex-fill flex-sm-grow-0" href="{{ route('toapp.agent.applications.index') }}">@lang('Back to applications')</a>
            @if($client)
                <form method="POST" action="{{ route('toapp.agent.applications.store', $client) }}" class="flex-fill flex-sm-grow-0 d-flex">
                    @csrf
                    <button class="btn btn-outline-primary flex-fill" type="submit"><i class="ti ti-plus me-1"></i> @lang('Add package')</button>
                </form>
                <a class="btn btn-primary flex-fill flex-sm-grow-0" href="{{ route('toapp.agent.chat.show', $client) }}"><i class="ti ti-messages me-1"></i> @lang('Open chat')</a>
            @endif
        </div>
    </div>
    <div class="list-group list-group-flush portal-detail-list">
        @foreach([
            [__('ZIP code'), $application->zipcode ?: '—'],
            [__('Product'), $application->plan?->name ?? '—'],
            [__('Sex'), $sexLabel],
            [__('Smoking'), ((int) $application->smoking === 1) ? __('Yes') : __('No')],
            [__('Health'), $healthLabel],
            [__('Chronic condition'), $application->chronic_disease ? __('Yes') : __('No')],
            [__('Coverage amount'), $application->coverage_amount ? '$' . number_format((int) $application->coverage_amount) : '—'],
            [__('Coverage length'), $application->coverage_length ? $application->coverage_length . ' ' . __('years') : '—'],
            [$livingLabel, $application->living_benefits ? __('Yes') : __('No')],
            [__('Pay type'), $application->pay_type === 'month' ? __('Monthly') : ($application->pay_type === 'year' ? __('Annual') : '—')],
            [__('Annual quote'), '$' . number_format((float) ($application->quoted_annual ?? 0), 2) . '/yr'],
            [__('Monthly quote'), '$' . number_format((float) ($application->quoted_monthly ?? 0), 2) . '/mo'],
            [__('Status'), $application->statusLabel()],
        ] as [$label, $value])
            <div class="list-group-item portal-detail-row">
                <span class="text-secondary">{{ $label }}</span>
                <strong>{{ $value }}</strong>
            </div>
        @endforeach
    </div>
</div>

@unless($isConfirmed)
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title mb-0">@lang('Edit quote & conditions')</h3>
    </div>
    <div class="card-body">
        <p class="text-secondary mb-4">
            @lang('Adjust the customer’s underwriting conditions and set the price yourself. You can load a suggested plan price, then override it.')
        </p>

        <form method="POST" action="{{ route('toapp.agent.applications.quote', $application) }}" id="agent-quote-form">
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
                                        data-monthly="{{ $plan->monthlyPriceForRange($range) }}"
                                        data-annual="{{ $plan->annualPriceForRange($range) }}"
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
                    <p class="text-secondary small mb-0">@lang('Underwriting inputs for this quote.')</p>
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
                        <p class="text-secondary small mb-0">@lang('Enter the final quote to show the customer.')</p>
                    </div>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="fill-plan-price">
                        @lang('Load suggested plan price')
                    </button>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="quoted-monthly">@lang('Monthly quote ($)')</label>
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        class="form-control"
                        id="quoted-monthly"
                        name="quoted_monthly"
                        value="{{ old('quoted_monthly', $application->quoted_monthly ?? 0) }}"
                        required
                    >
                    @error('quoted_monthly')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="quoted-annual">@lang('Annual quote ($)')</label>
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        class="form-control"
                        id="quoted-annual"
                        name="quoted_annual"
                        value="{{ old('quoted_annual', $application->quoted_annual ?? 0) }}"
                        required
                    >
                    @error('quoted_annual')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label" for="agent-notes">@lang('Notes (optional)')</label>
                    <textarea class="form-control" id="agent-notes" name="agent_notes" rows="2" placeholder="@lang('Why this price or condition was chosen')">{{ old('agent_notes', $application->admin_notes) }}</textarea>
                </div>
            </div>

            <button class="btn btn-primary" type="submit">@lang('Save quote & conditions')</button>
        </form>
    </div>
</div>
@endunless

<div class="card">
    <div class="card-header portal-card-header-stacked">
        <div class="card-subtitle">@lang('Offline payment')</div>
        <h3 class="card-title mb-0">@lang('Confirm after payment')</h3>
    </div>
    <div class="card-body">
        @if($isConfirmed)
            <div class="alert alert-success mb-0">
                @lang('Policy confirmed on') {{ optional($application->payment_confirmed_at)->format('M d, Y H:i') }}.
            </div>
        @else
            <p class="text-secondary mb-4">
                @lang('The client completes payment outside the app. Mark the application when payment is expected, then confirm once the policy is sold.')
            </p>

            <div class="portal-action-block mb-4">
                <div class="portal-action-block-head">
                    <h4 class="mb-1">@lang('Step 1 — Awaiting payment')</h4>
                    <p class="text-secondary mb-0">@lang('Use this when the client has been instructed to pay but confirmation is not done yet.')</p>
                </div>
                <form method="POST" action="{{ route('toapp.agent.applications.payment_pending', $application) }}" class="mt-3">
                    @csrf
                    <button class="btn btn-outline-primary" type="submit">@lang('Mark awaiting payment')</button>
                </form>
            </div>

            <div class="portal-action-block">
                <div class="portal-action-block-head">
                    <h4 class="mb-1">@lang('Step 2 — Confirm policy sold')</h4>
                    <p class="text-secondary mb-0">@lang('Record notes and confirm after offline payment is received.')</p>
                </div>
                <form method="POST" action="{{ route('toapp.agent.applications.confirm', $application) }}" class="mt-3">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="payment-notes">@lang('Notes')</label>
                        <textarea class="form-control" id="payment-notes" name="notes" rows="3" placeholder="@lang('Payment reference, amount received, or follow-up details')"></textarea>
                    </div>
                    <button class="btn btn-primary" type="submit">@lang('Confirm policy sold')</button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
@media (max-width: 575.98px) {
    .agent-app-detail-actions .btn {
        min-height: 44px;
        font-weight: 600;
    }
}
</style>
@endpush

@unless($isConfirmed)
@push('scripts')
@php
    $agentApplicationI18n = [
        'livingBenefits' => __('Living Benefits'),
        'longTermCare' => __('Long term care'),
    ];
@endphp
<script>
(function () {
    const planSelect = document.getElementById('plan-id');
    const lengthWrap = document.getElementById('coverage-length-wrap');
    const lengthSelect = document.getElementById('coverage-length');
    const livingLabel = document.getElementById('living-benefits-label');
    const monthlyInput = document.getElementById('quoted-monthly');
    const annualInput = document.getElementById('quoted-annual');
    const fillBtn = document.getElementById('fill-plan-price');
    const planPrices = @json($planPrices ?? []);
    const priceRange = document.getElementById('price-range');
    const i18n = @json($agentApplicationI18n);

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
    priceRange?.addEventListener('change', function () {
        // Keep manual prices unless agent clicks load.
    });
    fillBtn?.addEventListener('click', fillSuggestedPrice);
    syncCoverageLength();
})();
</script>
@endpush
@endunless
