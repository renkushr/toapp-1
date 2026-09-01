@extends('check_insure.layout')

@section('page_class', 'page-dark')
@section('content')
@php
    $payType = ($criteria['pay_type'] ?? 'year') === 'month' ? 'month' : 'year';
    $range = (int) ($criteria['price_range'] ?? 1);
    $coverageLength = (int) ($criteria['coverage_length'] ?? 0);
    $isTermLife = ($criteria['product_category'] ?? $plan->category) === 'Term Life';
    $summary = '$' . number_format($plan->matched_price, 0) . ' / ' . ($payType === 'month' ? 'month' : 'year');
    if ($isTermLife && $coverageLength) {
        $summary .= ' · Length ' . $coverageLength . ' years';
    }
@endphp
<div class="section p-0">
    <div class="container">
        <form class="card card-form infos" method="POST" action="{{ route('checkinsure.plans.select', $plan) }}">
            @csrf
            <input type="hidden" name="price_range" value="{{ $range }}">
            <div class="card-body w-100">
                <div class="d-block text-center">
                    <h1 class="title-md">{{ $plan->name }}</h1>
                    <p class="fs-18 mt-1" style="color: #636363;">{{ $summary }}</p>
                </div>

                <div class="card-article">
                    <div class="scrollbar-inner">
                        {!! $plan->term ?: '<h3>Benefits of ' . e($plan->name) . '</h3><p>Terms will be provided by your agent.</p>' !!}
                    </div>
                </div>

                <div class="boxed text-center my-4 pt-2" style="--max-width:505px">
                    <div class="buttons mt-sm-2 flex-column gap-3">
                        <button class="btn btn-primary w-200 fs-18" type="submit">
                            <span>Select and chat with agent</span>
                        </button>
                        <a class="btn btn-link fs-18 mt-3" href="{{ route('checkinsure.results') }}">
                            <span>Back</span>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
