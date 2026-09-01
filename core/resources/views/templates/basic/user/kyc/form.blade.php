@extends($activeTemplate.'layouts.master')
@section('content')
<div class="app-page">
    <div class="app-container">
        @include($activeTemplate . 'partials.app_topbar', [
            'backUrl' => route('user.setting'),
            'brandTitle' => __('KYC Verification'),
        ])
        <section class="py-3">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card custom--card">
                        <div class="card-body">
                            <form action="{{route('user.kyc.submit')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <x-viser-form identifier="act" identifierValue="kyc" />
                                <div class="form-group">
                                    <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
@push('style-lib')
<link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
<script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush
