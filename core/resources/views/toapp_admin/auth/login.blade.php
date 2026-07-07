@extends('toapp_admin.layouts.auth')

@section('content')
<div class="portal-auth">
    <section class="portal-auth-hero">
        <div class="portal-auth-hero-inner">
            <div class="portal-auth-brand mb-4">
                @include('partials.portal_brand_logo', [
                    'href' => route('toapp.admin.login'),
                    'variant' => 'light',
                ])
            </div>
            <h1>Run insurance operations from one modern console.</h1>
            <p>Manage products, members, agents, applications, and finance with a workspace built for daily operations.</p>
        </div>
    </section>

    <section class="portal-auth-panel">
        <div class="portal-auth-card">
            @include('partials.portal_brand_logo', [
                'href' => route('toapp.admin.login'),
                'variant' => 'default',
            ])
            <h2>Admin sign in</h2>
            <p class="text-secondary mb-4">Welcome back. Enter your credentials to continue.</p>

            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('toapp.admin.login.submit') }}" autocomplete="off">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" value="{{ old('username') }}" autocomplete="username" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" autocomplete="current-password" required>
                </div>
                <div class="mb-4">
                    <label class="form-check">
                        <input type="checkbox" class="form-check-input" name="remember" value="1" @checked(old('remember'))>
                        <span class="form-check-label">Remember this device</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Sign in</button>
            </form>
        </div>
    </section>
</div>
@endsection
