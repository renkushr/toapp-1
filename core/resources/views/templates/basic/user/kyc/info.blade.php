@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="app-page">
        <div class="app-container">
            @include($activeTemplate . 'partials.app_topbar', [
                'backUrl' => route('user.setting'),
                'brandTitle' => __('KYC Data'),
            ])
            <section class="py-3">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card custom--card">
                            <div class="card-body">
                                @if ($user->kyc_data)
                                    <ul class="list-group list-group-flush">
                                        @foreach ($user->kyc_data as $val)
                                            @continue(!$val->value)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                {{ __($val->name) }}
                                                <span>
                                                    @if ($val->type == 'checkbox')
                                                        {{ implode(',', $val->value) }}
                                                    @elseif($val->type == 'file')
                                                        <a class="me-3" href="{{ route('user.download.attachment', encrypt(getFilePath('verify') . '/' . $val->value)) }}"><i class="fa fa-file"></i> @lang('Attachment') </a>
                                                    @else
                                                        <p>{{ __($val->value) }}</p>
                                                    @endif
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <h5 class="text-center">@lang('KYC data not found')</h5>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .list-group-flush>.list-group-item {
            border-width: 0 0 1px !important;
        }

        .list-group-flush :last-child {
            border-width: 0 !important;
        }
    </style>
@endpush
