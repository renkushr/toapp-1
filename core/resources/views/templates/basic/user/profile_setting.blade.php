@extends($activeTemplate . 'layouts.master')

@section('content')
<div class="app-page">
    <div class="app-container">
        <div class="app-topbar">
            <a class="app-icon-btn" href="{{ route('user.setting') }}" aria-label="@lang('Back to settings')">
                <i class="las la-arrow-left"></i>
            </a>
            <a class="app-brand" href="{{ route('user.home') }}">
                <img src="{{ siteLogo() }}" alt="To-app">
                <span>@lang('Profile')</span>
            </a>
            <a class="app-icon-btn" href="{{ route('ticket.open') }}" aria-label="@lang('Support')">
                <i class="las la-headset"></i>
            </a>
        </div>

        <section class="app-hero">
            <span class="app-eyebrow"><i class="las la-user-check"></i> @lang('Personal information')</span>
            <h1>@lang('Keep your account details accurate.')</h1>
            <p>@lang('These details help admin verify deposits, withdrawals, and support requests without extra back-and-forth.')</p>
            <div class="app-pills">
                <span class="app-pill"><i class="las la-envelope"></i>{{ $user->email }}</span>
                <span class="app-pill"><i class="las la-user"></i>{{ $user->username }}</span>
            </div>
        </section>

        <div class="app-section-title">
            <div>
                <h2>@lang('Edit profile')</h2>
                <p>@lang('Update only the details that changed.')</p>
            </div>
        </div>

        <div class="app-card">
            <form action="{{ route('user.profile.setting.update') }}" method="post" class="app-form-grid">
                @csrf
                <label class="app-field">
                    <span>@lang('First name')</span>
                    <input type="text" name="firstname" value="{{ old('firstname', $user->firstname) }}" class="form-control" required>
                </label>

                <label class="app-field">
                    <span>@lang('Last name')</span>
                    <input type="text" name="lastname" value="{{ old('lastname', $user->lastname) }}" class="form-control" required>
                </label>

                <label class="app-field">
                    <span>@lang('Email')</span>
                    <input type="text" value="{{ $user->email }}" class="form-control" disabled>
                    <small>@lang('Email changes must be handled by support for safety.')</small>
                </label>

                <label class="app-field">
                    <span>@lang('Phone number')</span>
                    <input type="text" name="mobile" value="{{ old('mobile', $user->mobile) }}" class="form-control" required>
                </label>

                <label class="app-field">
                    <span>@lang('ZIP code')</span>
                    <input type="text" name="zipcode" value="{{ old('zipcode', $user->zipcode) }}" class="form-control" required>
                </label>

                <button class="app-btn app-btn-primary w-100" type="submit">
                    <i class="las la-save"></i>
                    @lang('Save profile')
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
