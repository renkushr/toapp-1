@extends('check_insure.layout')

@section('page_class', 'page-dark')
@section('content')
@php
    $payType = old('pay_type', 'year');
    $productCategory = old('product_category', optional($productTypes[0] ?? null)['category'] ?? '');
    $coverageLength = (int) old('coverage_length', 20);
    $health = (int) old('health', 1);
    $smoking = (int) old('smoking', 0);
    $isTermLife = $productCategory === 'Term Life';
    $priceByCategory = collect($productTypes)->keyBy('category');
@endphp
<div class="section p-0">
    <div class="container">
        <form class="card card-form" method="POST" action="{{ route('checkinsure.survey.submit') }}">
            @csrf
            @include('check_insure.partials.wizard_steps', ['activeStep' => 2])

            @if(session('checkinsure_no_agent'))
                <div class="alert alert-warning text-start mx-auto mb-3" style="max-width: 520px;">
                    No agent is available for your ZIP code yet. You can continue your application and our team will assign an agent later.
                </div>
            @endif

            <div class="card-body text-center">
                <div class="boxed" style="--max-width:330px">
                    <div class="row check-lists gy-sm-4 gy-3">
                        <div class="col-12">
                            <h1 class="title-md">How’s your health?</h1>
                        </div>
                        <div class="col-12 radio d-flex">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="health" id="health-standard" value="2" @checked($health === 2)>
                                <label class="form-check-label" for="health-standard">Standard</label>
                            </div>
                            <div class="form-check ms-auto">
                                <input class="form-check-input" type="radio" name="health" id="health-healthy" value="1" @checked($health === 1)>
                                <label class="form-check-label" for="health-healthy">Healthy</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex">
                            <div class="form-check mx-auto">
                                <input type="hidden" name="smoking" value="0">
                                <input class="form-check-input" type="checkbox" name="smoking" id="smoking" value="1" @checked($smoking === 1)>
                                <label class="form-check-label" for="smoking">
                                    I’m smoking <span class="nowrap">(Current nicotine use)</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-12 d-flex">
                            <div class="form-check mx-auto">
                                <input type="hidden" name="chronic_disease" value="0">
                                <input class="form-check-input" type="checkbox" name="chronic_disease" id="chronic_disease" value="1" @checked(old('chronic_disease'))>
                                <label class="form-check-label" for="chronic_disease">
                                    Pre-existing / chronic condition
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="boxed mt-4 pt-sm-2" style="--max-width:435px">
                    <h1 class="title-md mb-3">What’s your interested?</h1>
                    <input type="hidden" name="pay_type" id="pay-type-input" value="{{ $payType }}">
                    <div class="buttons pay">
                        <button class="btn btn-outline btn-pay {{ $payType === 'year' ? 'active' : '' }}" type="button" data-pay="year">
                            <span>Annual pay insurance</span>
                        </button>
                        <button class="btn btn-outline btn-pay {{ $payType === 'month' ? 'active' : '' }}" type="button" data-pay="month">
                            <span>Monthly pay insurance</span>
                        </button>
                    </div>
                </div>

                <div class="boxed mt-sm-5 mt-4 pt-2 pt-sm-0" style="--max-width:760px">
                    <h1 class="title-md mb-3">Choose your insurance type</h1>
                    <input type="hidden" name="product_category" id="product-category-input" value="{{ $productCategory }}">
                    <input type="hidden" name="price_range" value="1">

                    @if($productTypes)
                        <div class="row card-pricing-lists">
                            @foreach($productTypes as $type)
                                @php
                                    $category = $type['category'];
                                    $fromPrice = $payType === 'month' ? $type['from_monthly'] : $type['from_annual'];
                                    $isActive = $productCategory === $category;
                                @endphp
                                <div class="col-sm-4">
                                    <div class="card-pricing {{ $isActive ? 'active' : '' }}" data-category="{{ $category }}" role="button" tabindex="0">
                                        <h3 class="card-title">{{ $category }}</h3>
                                        <div class="card-body">
                                            <p>{{ $type['plan_count'] }} {{ Str::plural('plan', $type['plan_count']) }} available</p>
                                            <h2 class="js-from-price">{{ $fromPrice ? '$' . number_format($fromPrice, 0) : '—' }}</h2>
                                            <p class="js-pay-label">{{ $payType === 'month' ? 'From / month' : 'From / year' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning text-start">
                            No insurance products are published for Check Insure yet. Please contact support or try again later.
                        </div>
                    @endif

                    <div class="text-start mx-auto mt-3" id="coverage-length-field" style="max-width:320px; {{ $isTermLife ? '' : 'display:none;' }}">
                        <label class="form-label">Coverage Length</label>
                        <select class="form-control form--control" name="coverage_length" id="coverage-length-select" @if($isTermLife) required @endif>
                            @foreach([10, 15, 20, 25, 30] as $years)
                                <option value="{{ $years }}" @selected($coverageLength === $years)>{{ $years }} years</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="buttons mt-sm-4 my-2 flex-column gap-3">
                        <a class="btn btn-link fs-18" href="{{ route('checkinsure.profile') }}">
                            <span>Back</span>
                        </a>
                        <button class="btn btn-primary fs-18" type="submit" @disabled(!$productTypes)>
                            <span>Here’s your details</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const productPrices = @json($priceByCategory);

function formatPrice(value) {
    if (!value) return '—';
    return '$' + Number(value).toLocaleString(undefined, { maximumFractionDigits: 0 });
}

function refreshProductPrices() {
    const payType = $('#pay-type-input').val();

    $('.card-pricing[data-category]').each(function () {
        const category = $(this).data('category');
        const info = productPrices[category];
        if (!info) return;

        const price = payType === 'month' ? info.from_monthly : info.from_annual;
        $(this).find('.js-from-price').text(formatPrice(price));
        $(this).find('.js-pay-label').text(payType === 'month' ? 'From / month' : 'From / year');
    });
}

function syncCoverageLength() {
    const isTermLife = $('#product-category-input').val() === 'Term Life';
    const $field = $('#coverage-length-field');
    const $select = $('#coverage-length-select');

    $field.toggle(isTermLife);
    $select.prop('required', isTermLife);
    if (!isTermLife) {
        $select.val('');
    } else if (!$select.val()) {
        $select.val('20');
    }
}

$('.btn-pay').on('click', function () {
    $('.btn-pay').removeClass('active');
    $(this).addClass('active');
    $('#pay-type-input').val($(this).data('pay'));
    refreshProductPrices();
});

function selectProductCard($card) {
    $('.card-pricing').removeClass('active');
    $card.addClass('active');
    $('#product-category-input').val($card.data('category'));
    syncCoverageLength();
}

$('.card-pricing').on('click', function () {
    selectProductCard($(this));
});

$('.card-pricing').on('keydown', function (e) {
    if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        selectProductCard($(this));
    }
});

syncCoverageLength();
</script>
@endpush
