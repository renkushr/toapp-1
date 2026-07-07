@extends('check_insure.layout')

@section('page_class', 'page-dark')
@section('content')
<div class="section p-0">
    <div class="container">
        <div class="card card-form">
            @include('check_insure.partials.wizard_steps', ['activeStep' => 3])

            <div class="card-body text-center">
                <div class="boxed" style="--max-width:735px">
                    <h1 class="title-md">Your Result is here</h1>

                    @if($plans->isEmpty())
                        <div class="alert alert-warning mt-4">No published products match your criteria right now.</div>
                        <a href="{{ route('checkinsure.survey') }}" class="btn btn-outline-primary mt-3">Adjust survey</a>
                    @else
                        <div class="row pricing-info-lists">
                            @foreach($plans as $index => $plan)
                                <div class="col-lg-4 col-sm-6" data-aos="fade-up" @if($index) data-aos-delay="{{ $index * 100 }}" @endif>
                                    <div class="card-pricing-infos">
                                        <h3 class="card-title">{{ $plan->name }}</h3>
                                        <div class="card-body">
                                            <div class="price">
                                                <h5>$ {{ number_format($plan->matched_price, 0) }}</h5>
                                                <p>per {{ ($criteria['pay_type'] ?? 'year') === 'month' ? 'month' : 'year' }}</p>
                                            </div>
                                            <p>{{ $plan->descript ?: 'Maximum benefit amount for in-patient per Confinement' }}</p>
                                            <ul class="lists">
                                                @if($plan->category)
                                                    <li>{{ $plan->category }}</li>
                                                @endif
                                                <li>Coverage matched to your profile</li>
                                                <li>Agent-assisted enrollment</li>
                                            </ul>
                                            <div class="buttons">
                                                <a class="btn btn-primary fs-18" href="{{ route('checkinsure.plans.show', $plan) }}">
                                                    <span>Full details and<br>adjustment</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="buttons mt-sm-4 my-2 flex-column gap-3">
                    <a class="btn btn-link fs-18" href="{{ route('checkinsure.survey') }}">
                        <span>Back</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
