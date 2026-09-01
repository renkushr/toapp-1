@extends($activeTemplate . 'layouts.master')

@section('content')
@php
    $fullName = trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? '')) ?: $user->username;
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
<div class="app-page">
    <div class="app-container">
        <div class="app-topbar">
            <a class="app-icon-btn" href="{{ route('user.home') }}" aria-label="@lang('Back')">
                <i class="las la-arrow-left"></i>
            </a>
            <a class="app-brand" href="{{ route('user.home') }}">
                <img src="{{ siteLogo() }}" alt="To-app">
                <span>@lang('Plans')</span>
            </a>
            <span class="app-icon-btn" style="visibility:hidden"><i class="las la-layer-group"></i></span>
        </div>

        <section class="app-hero">
            <span class="app-eyebrow"><i class="las la-user"></i> @lang('Step 1 of 3')</span>
            <h1>@lang('Hello'), {{ $fullName }}</h1>
            <p>@lang('Tell us a few details so we can match the right insurance products.')</p>
        </section>

        @include($activeTemplate . 'user.insurance.partials.steps', ['activeStep' => 1])

        <form method="POST" action="{{ route('user.plans.profile.submit') }}" class="app-card app-form-grid">
            @csrf

            <div>
                <label class="app-field-label">@lang('You are')</label>
                <input type="hidden" name="sex" id="sex-input" value="{{ $selectedSex ?: '' }}" required>
                <div class="row g-2">
                    <div class="col-6">
                        <button class="app-btn w-100 sex-btn {{ $selectedSex === 1 ? 'app-btn-primary' : 'app-btn-secondary' }}" type="button" data-sex="1">
                            <i class="las la-male"></i> @lang('Male')
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="app-btn w-100 sex-btn {{ $selectedSex === 2 ? 'app-btn-primary' : 'app-btn-secondary' }}" type="button" data-sex="2">
                            <i class="las la-female"></i> @lang('Female')
                        </button>
                    </div>
                </div>
                @error('sex')<div class="text-danger mt-2">{{ $message }}</div>@enderror
            </div>

            <label class="app-field">
                <span>@lang('Date of Birth')</span>
                <input class="form-control" type="text" name="birthday" placeholder="MM/DD/YYYY" value="{{ $birthdayValue }}" required>
                @error('birthday')<small class="text-danger">{{ $message }}</small>@enderror
            </label>

            <label class="app-field">
                <span>@lang('ZIP code')</span>
                <input class="form-control" type="text" name="zipcode" value="{{ old('zipcode', $user->zipcode) }}" pattern="\d{5}(-\d{4})?" required>
                @error('zipcode')<small class="text-danger">{{ $message }}</small>@enderror
            </label>

            <button class="app-btn app-btn-primary w-100" type="submit">
                @lang('Next') <i class="las la-arrow-right"></i>
            </button>
        </form>
    </div>
</div>
@endsection

@push('script')
<script>
(function() {
    const input = document.getElementById('sex-input');
    document.querySelectorAll('.sex-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            input.value = this.dataset.sex;
            document.querySelectorAll('.sex-btn').forEach(function(b) {
                b.classList.remove('app-btn-primary');
                b.classList.add('app-btn-secondary');
            });
            this.classList.remove('app-btn-secondary');
            this.classList.add('app-btn-primary');
        });
    });
})();
</script>
@endpush
