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
                <form class="form form-insure text-white" method="POST" action="{{ route('checkinsure.register.submit') }}">
                    @csrf
                    <div class="form-header">
                        <h1 class="h1" data-aos>
                            <span class="text-highlight">Find your insurance</span>
                        </h1>
                        <p class="m-0">
                            <strong>A smarter choice for your family , <span class="nowrap">Check your insurance</span></strong>
                        </p>
                    </div>
                    <div class="form-body">
                        <div class="row g-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="title">First Name</label>
                                    <input type="text" name="firstname" class="form-control" value="{{ old('firstname') }}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="title">Last Name</label>
                                    <input type="text" name="lastname" class="form-control" value="{{ old('lastname') }}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="title">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="title">Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="title">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-footer text-center">
                        <div class="buttons flex-column gap-2 p-0">
                            <button class="btn btn-primary" type="submit">
                                <span>Create account</span>
                            </button>
                            <p class="m-0 mt-2">
                                Already have an account?
                                <a class="text-white" href="{{ route('checkinsure.login') }}"><strong>Sign in</strong></a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('check_insure.partials.marketing')
@endsection
