@extends('check_insure.layout')

@section('page_class', '')
@section('content')
<div class="section section-insure-form">
    <div class="insure-form-boxed">
        <div class="row">
            <div class="cols left">
                <img data-aos="fade-up" src="{{ asset('check-insure/img/thumb/photo-1025x715.jpg') }}" alt="">
            </div>
            <div class="cols right">
                <form class="form form-insure text-white" method="POST" action="{{ route('checkinsure.verify.submit') }}">
                    @csrf
                    <div class="form-header">
                        <h1 class="h1" data-aos>
                            <span class="text-highlight">Find your insurance</span>
                        </h1>
                        <p class="m-0">
                            <strong>A smarter choice for your family , <span class="nowrap">Check your insurance</span></strong>
                        </p>
                    </div>
                    <div class="form-body pt-0">
                        <div class="row g-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="title">Verify code</label>
                                    <input type="text" name="code" class="form-control" maxlength="6" value="{{ old('code') }}" required autofocus>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-footer text-center">
                        <div class="row g-4">
                            <div class="col-12">
                                <p class="m-0">Check your email and enter the 6-digit code we sent to <strong>{{ $user->email }}</strong></p>
                            </div>
                            <div class="col-12">
                                <div class="buttons flex-column gap-2 p-0">
                                    <button class="btn btn-primary" type="submit">
                                        <span>Verify Email</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <form method="POST" action="{{ route('checkinsure.verify.resend') }}" class="text-center mt-2">
                    @csrf
                    <button class="btn btn-link resend text-white" type="submit">
                        <img class="icons icon-resend svg-js" src="{{ asset('check-insure/img/icons/icon-resend.svg') }}" alt="">
                        Resend
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@include('check_insure.partials.marketing')
@endsection
