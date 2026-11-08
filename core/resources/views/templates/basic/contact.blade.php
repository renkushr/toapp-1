@php
    $contact = getContent('contact_us.content', true);
    $contactElement = getContent('contact_us.element');
@endphp

@extends($activeTemplate . 'layouts.frontend')

@section('content')
    @include($activeTemplate . 'partials.public_nav')

    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <h2 class="mb-3">{{ __(@$contact->data_values->heading) }}</h2>
                    <p>{{ __(@$contact->data_values->subheading) }}</p>
                </div>
            </div>
            <div class="row justify-content-between mt-3">
                <div class="col-lg-4">
                    <div class="row gy-4">
                        @foreach ($contactElement as $item)
                            <div class="col-lg-12">
                                <div class="contact-info-card rounded-3">
                                    <h6 class="title mb-3">{{ __($item->data_values->address_type) }}</h6>
                                    <div class="contact-info d-flex">
                                        @php echo $item->data_values->icon; @endphp
                                        <p>{{ __($item->data_values->address) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-8 ps-lg-5">
                    <div class="contact-wrapper rounded-3">
                        <form class="contact-form" method="post" action="">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>@lang('Name')</label>
                                        <div class="custom--field">
                                            <input name="name" type="text" class="form--control"
                                                value="@if (auth()->user()) {{ auth()->user()->fullname }} @else {{ old('name') }} @endif"
                                                @if (auth()->user()) readonly @endif required>
                                            <i class="la la-user"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>@lang('Email')</label>
                                        <div class="custom--field">
                                            <input name="email" type="email" class="form--control"
                                                value="@if (auth()->user()) {{ auth()->user()->email }} @else {{ old('email') }} @endif"
                                                @if (auth()->user()) readonly @endif required>
                                            <i class="la la-envelope"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>@lang('Subject')</label>
                                        <div class="custom--field">
                                            <input name="subject" type="text" class="form--control"
                                                value="{{ old('subject') }}" required>
                                            <i class="la la-sticky-note"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>@lang('Message')</label>
                                    <div class="custom--field">
                                        <textarea name="message" class="form--control" required>{{ old('message') }}</textarea>
                                        <i class="la la-sms"></i>
                                    </div>
                                </div>
                            </div>

                            <x-captcha />

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
<style>
body {
    background:
        radial-gradient(circle at 8% -10%, rgba(19, 200, 214, .22), transparent 38%),
        radial-gradient(circle at 92% 0%, rgba(18, 104, 243, .16), transparent 36%),
        linear-gradient(180deg, #f7fbff 0%, #eef6fb 42%, #f8fbff 100%) !important;
}
</style>
@endpush
