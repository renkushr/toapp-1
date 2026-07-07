@extends('check_insure.layout')

@section('page_class', 'page-dark')
@section('content')
<div class="section p-0">
    <div class="container">
        <form class="card card-form infos" method="POST" action="{{ route('checkinsure.terms.confirm', $application) }}">
            @csrf
            <div class="card-body w-100">
                <div class="d-block text-center">
                    <h1 class="title-md">Terms & confirmation</h1>
                    <p class="fs-18 mt-1" style="color: #636363;">Product: {{ $application->plan?->name }}</p>
                </div>

                <div class="card-article">
                    <div class="scrollbar-inner">
                        {!! $application->plan?->term ?: '<p>Terms will be provided by your agent.</p>' !!}
                    </div>
                </div>

                <div class="boxed text-center my-4 pt-2" style="--max-width:505px">
                    <label class="d-flex gap-2 align-items-start text-start mb-4">
                        <input type="checkbox" name="accept" value="1" required>
                        <span>I have read the terms. Payment will be completed outside this website. My assigned agent will confirm after payment.</span>
                    </label>
                    <button class="btn btn-primary w-200 fs-18" type="submit">
                        <span>Confirm & chat with agent</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
