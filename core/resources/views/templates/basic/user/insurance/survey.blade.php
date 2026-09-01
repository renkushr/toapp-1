@extends($activeTemplate . 'layouts.master')

@section('content')
@php
    $payType = old('pay_type', $criteria['pay_type'] ?? 'year');
    $productCategory = old('product_category', $criteria['product_category'] ?? (optional($productTypes[0] ?? null)['category'] ?? ''));
    $coverageLength = (int) old('coverage_length', $criteria['coverage_length'] ?? 20);
    $coverageAmount = (int) old('coverage_amount', $criteria['coverage_amount'] ?? 100000);
    $livingBenefits = (string) old('living_benefits', isset($criteria['living_benefits']) ? ((int) $criteria['living_benefits']) : '0');
    $health = (int) old('health', $criteria['health'] ?? 1);
    $smoking = (int) old('smoking', $criteria['smoking'] ?? ($user->nicotin ?? 0));
    $selectedSex = (int) old('sex', $user->sex ?: 0);
@endphp
<div class="app-page">
    <div class="app-container">
        <div class="app-topbar">
            <a class="app-icon-btn" href="{{ route('user.home') }}" aria-label="@lang('Back')">
                <i class="las la-arrow-left"></i>
            </a>
            <a class="app-brand" href="{{ route('user.home') }}">
                <img src="{{ siteLogo() }}" alt="To-app">
                <span>@lang('Plans')</span>
            </a>
            <span class="app-icon-btn" style="visibility:hidden"><i class="las la-shield-alt"></i></span>
        </div>

        <section class="app-hero">
            <span class="app-eyebrow"><i class="las la-shield-alt"></i> @lang('Insurance quote')</span>
            <h1>@lang('Choose a plan that fits your needs.')</h1>
            <p>@lang('Select your coverage on this page, then get a quote right away.')</p>
            @if($hasBirthday)
                <div class="app-pills">
                    <span class="app-pill"><i class="las la-birthday-cake"></i> @lang('DOB') {{ \Carbon\Carbon::parse($user->birthday)->format('M d, Y') }}</span>
                    @if($user->zipcode)
                        <span class="app-pill"><i class="las la-map-marker"></i> ZIP code {{ $user->zipcode }}</span>
                    @endif
                </div>
            @endif
        </section>

        <form method="POST" action="{{ route('user.plans.survey.submit') }}" class="app-form-grid" id="plans-quote-form">
            @csrf

            @if($needsSex || $needsZip || !$hasBirthday)
                <div class="app-card">
                    <h2 class="fs-5 fw-bold mb-3">@lang('Your details')</h2>

                    @if($needsSex)
                        <div class="mb-3">
                            <label class="app-field-label">@lang('You are')</label>
                            <input type="hidden" name="sex" id="sex-input" value="{{ $selectedSex ?: '' }}" required>
                            <div class="row g-2">
                                <div class="col-6">
                                    <button class="app-btn w-100 sex-btn {{ $selectedSex === 1 ? 'app-btn-primary' : 'app-btn-secondary' }}" type="button" data-sex="1">
                                        <i class="las la-male"></i> @lang('Male')
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button class="app-btn w-100 sex-btn {{ $selectedSex === 2 ? 'app-btn-primary' : 'app-btn-secondary' }}" type="button" data-sex="2">
                                        <i class="las la-female"></i> @lang('Female')
                                    </button>
                                </div>
                            </div>
                            @error('sex')<div class="text-danger mt-2">{{ $message }}</div>@enderror
                        </div>
                    @endif

                    @unless($hasBirthday)
                        <label class="app-field mb-3">
                            <span>@lang('Date of Birth')</span>
                            <input type="date" name="birthday" value="{{ old('birthday') }}" max="{{ now()->subYears(18)->format('Y-m-d') }}" required>
                        </label>
                        @error('birthday')<div class="text-danger mb-2">{{ $message }}</div>@enderror
                    @endunless

                    @if($needsZip)
                        <label class="app-field">
                            <span>@lang('ZIP code')</span>
                            <input type="text" name="zipcode" value="{{ old('zipcode', $user->zipcode) }}" pattern="\d{5}(-\d{4})?" required>
                        </label>
                        @error('zipcode')<div class="text-danger mt-2">{{ $message }}</div>@enderror
                    @endif
                </div>
            @endif

            <div class="app-card">
                <h2 class="fs-5 fw-bold mb-3">@lang("How's your health?")</h2>
                <div class="d-flex gap-3 flex-wrap">
                    <label class="app-pill" style="cursor:pointer;">
                        <input type="radio" name="health" value="2" @checked($health === 2)> @lang('Standard')
                    </label>
                    <label class="app-pill" style="cursor:pointer;">
                        <input type="radio" name="health" value="1" @checked($health === 1)> @lang('Healthy')
                    </label>
                </div>
                @error('health')<div class="text-danger mt-2">{{ $message }}</div>@enderror

                <div class="mt-3">
                    <input type="hidden" name="smoking" value="0">
                    <label class="d-flex align-items-center gap-2">
                        <input type="checkbox" name="smoking" value="1" @checked($smoking === 1)>
                        <span>@lang("I'm smoking (current nicotine use)")</span>
                    </label>
                </div>
                <div class="mt-2">
                    <input type="hidden" name="chronic_disease" value="0">
                    <label class="d-flex align-items-center gap-2">
                        <input type="checkbox" name="chronic_disease" value="1" @checked(old('chronic_disease', $criteria['chronic_disease'] ?? false))>
                        <span>@lang('Pre-existing / chronic condition')</span>
                    </label>
                </div>
            </div>

            <div class="app-card">
                <h2 class="fs-5 fw-bold mb-3">@lang('Payment preference')</h2>
                <input type="hidden" name="pay_type" id="pay-type-input" value="{{ $payType }}">
                <div class="row g-2">
                    <div class="col-6">
                        <button class="app-btn w-100 pay-btn {{ $payType === 'year' ? 'app-btn-primary' : 'app-btn-secondary' }}" type="button" data-pay="year">@lang('Annual')</button>
                    </div>
                    <div class="col-6">
                        <button class="app-btn w-100 pay-btn {{ $payType === 'month' ? 'app-btn-primary' : 'app-btn-secondary' }}" type="button" data-pay="month">@lang('Monthly')</button>
                    </div>
                </div>
            </div>

            <div class="app-card">
                <h2 class="fs-5 fw-bold mb-3">@lang('Choose insurance type')</h2>
                <input type="hidden" name="product_category" id="product-category-input" value="{{ $productCategory }}">
                <input type="hidden" name="price_range" value="1">

                @if($productTypes)
                    <div class="row g-2">
                        @foreach($productTypes as $type)
                            @php
                                $category = $type['category'];
                                $fromPrice = $payType === 'month' ? $type['from_monthly'] : $type['from_annual'];
                                $isActive = $productCategory === $category;
                            @endphp
                            <div class="col-md-6">
                                <button class="app-plan-card w-100 text-start category-card {{ $isActive ? 'featured' : '' }}" type="button" data-category="{{ $category }}" data-month="{{ $type['from_monthly'] }}" data-year="{{ $type['from_annual'] }}">
                                    <strong>{{ $category }}</strong>
                                    <div class="app-plan-return mt-2">
                                        <strong class="js-from-price">{{ $fromPrice ? '$' . number_format($fromPrice, 0) : '—' }}</strong>
                                        <span class="js-pay-label">{{ $payType === 'month' ? __('From / month') : __('From / year') }}</span>
                                    </div>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="app-empty-state">@lang('No insurance products are published yet.')</div>
                @endif
                @error('product_category')<div class="text-danger mt-2">{{ $message }}</div>@enderror

                @php $isTermLifeSelected = ($productCategory ?? '') === 'Term Life'; @endphp
                <div id="coverage-length-wrap"
                     class="mt-3"
                     data-visible="{{ $isTermLifeSelected ? '1' : '0' }}"
                     @unless($isTermLifeSelected) hidden @endunless>
                    <label class="app-field app-field-full mb-0">
                        <span>@lang('Coverage Length')</span>
                        <select name="coverage_length" id="coverage-length-select"
                                @if($isTermLifeSelected) required @else disabled @endif>
                            @foreach([10, 15, 20, 25, 30] as $years)
                                <option value="{{ $years }}" @selected($coverageLength === $years)>{{ $years }} @lang('years')</option>
                            @endforeach
                        </select>
                    </label>
                    @error('coverage_length')<div class="text-danger mt-2">{{ $message }}</div>@enderror
                </div>
                <style>
                    #coverage-length-wrap[data-visible="0"],
                    #coverage-length-wrap[hidden] {
                        display: none !important;
                        visibility: hidden !important;
                        height: 0 !important;
                        max-height: 0 !important;
                        margin: 0 !important;
                        padding: 0 !important;
                        overflow: hidden !important;
                        border: 0 !important;
                    }
                </style>

                <label class="app-field app-field-full mt-3">
                    <span>@lang('Coverage Amount')</span>
                    <select name="coverage_amount" required>
                        @foreach($coverageAmounts as $amount)
                            <option value="{{ $amount }}" @selected($coverageAmount === (int) $amount)>${{ number_format($amount) }}</option>
                        @endforeach
                    </select>
                </label>
                @error('coverage_amount')<div class="text-danger mt-2">{{ $message }}</div>@enderror

                <div class="mt-3">
                    <label class="app-field-label d-block mb-2" id="living-benefits-label"
                           data-label-term="@lang('Living Benefits')"
                           data-label-iul="@lang('Long term care')">
                        {{ ($productCategory ?? '') === 'Term Life' ? __('Living Benefits') : __('Long term care') }}
                    </label>
                    <input type="hidden" name="living_benefits" id="living-benefits-input" value="{{ $livingBenefits }}">
                    <div class="row g-2">
                        <div class="col-6">
                            <button class="app-btn w-100 living-btn {{ $livingBenefits === '1' ? 'app-btn-primary' : 'app-btn-secondary' }}" type="button" data-living="1">@lang('Yes')</button>
                        </div>
                        <div class="col-6">
                            <button class="app-btn w-100 living-btn {{ $livingBenefits !== '1' ? 'app-btn-primary' : 'app-btn-secondary' }}" type="button" data-living="0">@lang('No')</button>
                        </div>
                    </div>
                    @error('living_benefits')<div class="text-danger mt-2">{{ $message }}</div>@enderror
                </div>
            </div>

            <button class="app-btn app-btn-primary w-100" type="submit" @disabled(!$productTypes)>
                @lang('Get a quote') <i class="las la-file-invoice-dollar"></i>
            </button>
        </form>
    </div>
</div>
@endsection

@push('script')
<script>
(function() {
    const payInput = document.getElementById('pay-type-input');
    const categoryInput = document.getElementById('product-category-input');
    const livingInput = document.getElementById('living-benefits-input');
    const sexInput = document.getElementById('sex-input');

    document.querySelectorAll('.sex-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (!sexInput) return;
            sexInput.value = this.dataset.sex;
            document.querySelectorAll('.sex-btn').forEach(function(b) {
                b.classList.remove('app-btn-primary');
                b.classList.add('app-btn-secondary');
            });
            this.classList.remove('app-btn-secondary');
            this.classList.add('app-btn-primary');
        });
    });

    document.querySelectorAll('.pay-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (!payInput) return;
            payInput.value = this.dataset.pay;
            document.querySelectorAll('.pay-btn').forEach(function(b) {
                b.classList.remove('app-btn-primary');
                b.classList.add('app-btn-secondary');
            });
            this.classList.remove('app-btn-secondary');
            this.classList.add('app-btn-primary');
            refreshPrices();
        });
    });

    document.querySelectorAll('.living-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (!livingInput) return;
            livingInput.value = this.dataset.living;
            document.querySelectorAll('.living-btn').forEach(function(b) {
                b.classList.remove('app-btn-primary');
                b.classList.add('app-btn-secondary');
            });
            this.classList.remove('app-btn-secondary');
            this.classList.add('app-btn-primary');
        });
    });

    document.querySelectorAll('.category-card').forEach(function(card) {
        card.addEventListener('click', function() {
            if (!categoryInput) return;
            categoryInput.value = this.dataset.category;
            document.querySelectorAll('.category-card').forEach(function(c) { c.classList.remove('featured'); });
            this.classList.add('featured');
            syncCoverageLength();
            syncLivingBenefitsLabel();
        });
    });

    function syncLivingBenefitsLabel() {
        const label = document.getElementById('living-benefits-label');
        if (!label) return;
        const category = ((categoryInput && categoryInput.value) || '').trim().toLowerCase();
        const isTermLife = category === 'term life';
        label.textContent = isTermLife
            ? (label.dataset.labelTerm || 'Living Benefits')
            : (label.dataset.labelIul || 'Long term care');
    }

    function syncCoverageLength() {
        const wrap = document.getElementById('coverage-length-wrap');
        // Layout script rewrites select ids to match name= — find by name inside the wrap.
        const select = wrap
            ? (wrap.querySelector('select[name="coverage_length"]') || wrap.querySelector('select'))
            : null;
        if (!wrap || !select) return;
        const category = ((categoryInput && categoryInput.value) || '').trim().toLowerCase();
        const isTermLife = category === 'term life';

        wrap.setAttribute('data-visible', isTermLife ? '1' : '0');
        if (isTermLife) {
            wrap.removeAttribute('hidden');
            select.disabled = false;
            select.required = true;
            if (!select.value) {
                select.value = '20';
            }
        } else {
            wrap.setAttribute('hidden', 'hidden');
            select.disabled = true;
            select.required = false;
        }
    }

    function refreshPrices() {
        if (!payInput) return;
        const pay = payInput.value;
        document.querySelectorAll('.category-card').forEach(function(card) {
            const price = pay === 'month' ? card.dataset.month : card.dataset.year;
            const label = card.querySelector('.js-from-price');
            const payLabel = card.querySelector('.js-pay-label');
            if (label) label.textContent = price ? ('$' + Number(price).toLocaleString()) : '—';
            if (payLabel) payLabel.textContent = pay === 'month' ? 'From / month' : 'From / year';
        });
    }

    syncCoverageLength();
    syncLivingBenefitsLabel();
})();
</script>
@endpush
