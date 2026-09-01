@extends('check_insure.layout')

@section('page_class', 'page-dark')
@section('content')
@php
    $fullName = trim($user->firstname . ' ' . $user->lastname) ?: $user->username;
    $birthdayValue = old('birthday');
    if (!$birthdayValue && $user->birthday) {
        try {
            $birthdayValue = \Carbon\Carbon::parse($user->birthday)->format('m/d/Y');
        } catch (\Throwable) {
            $birthdayValue = '';
        }
    }
    $selectedSex = (int) old('sex', $user->sex ?: 0);
@endphp
<div class="section p-0">
    <div class="container">
        <form class="card card-form" method="POST" action="{{ route('checkinsure.profile.submit') }}">
            @csrf
            @include('check_insure.partials.wizard_steps', ['activeStep' => 1])

            <div class="card-body text-center">
                <div class="boxed" style="--max-width:340px">
                    <h1 class="h1" data-aos>
                        <span class="text-highlight">Hello {{ $fullName }}</span>
                    </h1>
                    <h2 class="title-md mt-sm-2" data-aos="fade-in" data-aos-delay="200">Please fill your information</h2>

                    <div class="form-profile">
                        <p class="text-center" style="color: #252525;"><strong>You are</strong></p>
                        <input type="hidden" name="sex" id="sex-input" value="{{ $selectedSex ?: '' }}">

                        <div class="row g-lg-4 g-sm-3 g-2">
                            <div class="col-6">
                                <button class="btn btn-outline gender {{ $selectedSex === 1 ? 'active' : '' }}" type="button" data-sex="1">
                                    <img class="icons svg-js" src="{{ asset('check-insure/img/icons/icon-male.svg') }}" alt="">
                                    <span>male</span>
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-outline gender {{ $selectedSex === 2 ? 'active' : '' }}" type="button" data-sex="2">
                                    <img class="icons svg-js" src="{{ asset('check-insure/img/icons/icon-female.svg') }}" alt="">
                                    <span>female</span>
                                </button>
                            </div>
                        </div>
                        @error('sex')<div class="text-danger mt-2">{{ $message }}</div>@enderror

                        <div class="boxed">
                            <div class="row g-lg-4 g-3">
                                <div class="col-lg-7">
                                    <div class="form-group">
                                        <label class="title">Date of Birth</label>
                                        <div class="group">
                                            <span class="icons icon-arrow-down right"></span>
                                            <input type="text" name="birthday" class="form-control calendar" placeholder="MM/DD/YYYY" value="{{ $birthdayValue }}" required>
                                        </div>
                                        @error('birthday')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="title">ZIP code</label>
                                        <input type="text" name="zipcode" class="form-control" value="{{ old('zipcode', $user->zipcode) }}" pattern="\d{5}(-\d{4})?" required>
                                        @error('zipcode')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="buttons">
                            <button class="btn btn-primary fs-18 w-140" type="submit">
                                <span>NEXT</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('script-lib')
<script src="{{ asset('check-insure/js/bootstrap-datepicker.js') }}"></script>
@endpush

@push('scripts')
<script>
$(function () {
    $('.btn.gender').on('click', function () {
        $('.btn.gender').removeClass('active');
        $(this).addClass('active');
        $('#sex-input').val($(this).data('sex'));
    });

    $('input.calendar').datepicker({
        format: 'mm/dd/yyyy',
        autoclose: true,
        todayHighlight: false,
        assumeNearbyYear: false,
        startView: 2, // open on year picker first
        minViewMode: 0,
        maxViewMode: 3,
        startDate: '-100y',
        endDate: '-18y',
        defaultViewDate: { year: new Date().getFullYear() - 30, month: 0, day: 1 },
        orientation: 'bottom auto',
    });
});
</script>
@endpush
