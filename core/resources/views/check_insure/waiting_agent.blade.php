@extends('check_insure.layout')

@section('page_class', 'page-dark')
@section('content')
<div class="section p-0">
    <div class="container">
        <div class="card card-form infos">
            <div class="card-body w-100 text-center">
                <h1 class="title-md">Waiting for an agent</h1>
                <p class="fs-18 mt-2" style="color: #636363; max-width: 520px; margin: 0 auto;">
                    Your application is saved. There is no active agent for ZIP code <strong>{{ $user->zipcode }}</strong> yet.
                    Our team will assign an agent and contact you soon.
                </p>

                <div class="boxed mt-4 pt-2" style="--max-width:420px">
                    <p class="mb-3 text-secondary">Reference: {{ $application->reference }}</p>
                    <a class="btn btn-primary w-200 fs-18" href="{{ route('checkinsure.profile') }}">
                        <span>Back to profile</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
