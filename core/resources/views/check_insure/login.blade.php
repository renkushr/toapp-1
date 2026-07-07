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
                <form class="form form-insure text-white" method="POST" action="{{ route('checkinsure.login.submit') }}">
                    @csrf
                    <div class="form-header">
                        <h1 class="h1" data-aos>
                            <span class="text-highlight">Welcome back</span>
                        </h1>
                        <p class="m-0">
                            <strong>Sign in to continue your insurance quote</strong>
                        </p>
                    </div>
                    <div class="form-body">
                        <div class="row g-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="title">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" autocomplete="username" required autofocus>
                                    @error('email')<div class="text-warning mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="title">Password</label>
                                    <input type="password" name="password" class="form-control" autocomplete="current-password" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check text-start">
                                    <input class="form-check-input" type="checkbox" name="remember" value="1" id="remember" @checked(old('remember'))>
                                    <label class="form-check-label" for="remember">Remember me</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-footer text-center">
                        <div class="buttons flex-column gap-2 p-0">
                            <button class="btn btn-primary" type="submit">
                                <span>Sign in</span>
                            </button>
                            <p class="m-0 mt-2">
                                Don’t have an account?
                                <a class="text-white" href="{{ route('checkinsure.register') }}"><strong>Create account</strong></a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
