@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="app-page">
        <div class="app-container">
            @include($activeTemplate . 'partials.app_topbar', [
                'backUrl' => route('user.home'),
                'brandTitle' => __('Deposit History'),
            ])
            <section class="py-3">
        <div class="container-fluid px-0">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <form action="">
                        <div class="mb-3 d-flex justify-content-end">
                            <div>
                                <div class="input-group">
                                    <input type="search" name="search" value="{{ request()->search }}" class="form--control"
                                        placeholder="@lang('Search by transactions')">
                                    <button class="input-group-text bg--base border-0">
                                        <i class="la la-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive--md">
                        <table class="table custom--table">
                            <thead>
                                <tr>
                                    <th>@lang('Gateway | Transaction')</th>
                                    <th>@lang('Initiated')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Conversion')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Details')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deposits as $deposit)
                                    <tr>
                                        <td>
                                            <span class="fw-bold"> <span class="text--base">{{ __($deposit->gateway?->name) }}</span>
                                            </span>
                                            <br>
                                            <small> {{ $deposit->trx }} </small>
                                        </td>

                                        <td>
                                            {{ showDateTime($deposit->created_at) }}<br>{{ diffForHumans($deposit->created_at) }}
                                        </td>
                                        <td>
                                            {{ showAmount($deposit->amount) }} + <span class="text--danger"
                                                title="@lang('charge')">{{ showAmount($deposit->charge) }} </span>
                                            <br>
                                            <strong title="@lang('Amount with charge')">
                                                {{ showAmount($deposit->amount + $deposit->charge) }}

                                            </strong>
                                        </td>
                                        <td>
                                            1 {{ __(gs('cur_text')) }} = {{ showAmount($deposit->rate) }}
                                            {{ __($deposit->method_currency) }}
                                            <br>
                                            <strong>{{ showAmount($deposit->final_amount) }}
                                                {{ __($deposit->method_currency) }}</strong>
                                        </td>
                                        <td>
                                            @php echo $deposit->statusBadge @endphp
                                        </td>
                                        @php
                                            $details = [];
                                            if ($deposit->method_code >= 1000 && $deposit->method_code <= 5000) {
                                                foreach (@$deposit->detail ?? [] as $key => $info) {
                                                    $details[] = $info;
                                                    if ($info->type == 'file') {
                                                        $details[$key]->value = route(
                                                            'user.download.attachment',
                                                            encrypt(getFilePath('verify') . '/' . $info->value),
                                                        );
                                                    }
                                                }
                                            }
                                        @endphp
                                        <td>
                                            @if ($deposit->method_code >= 1000 && $deposit->method_code <= 5000)
                                                <a href="javascript:void(0)" class="btn btn--base btn-sm detailBtn"
                                                    data-info="{{ json_encode($details) }}"
                                                    @if ($deposit->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $deposit->admin_feedback }}" @endif>
                                                    <i class="fas fa-desktop"></i>
                                                </a>
                                            @else
                                                <button type="button" class="btn btn--base btn-sm" data-bs-toggle="tooltip" title="@lang('Automatically processed')">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($deposits->hasPages())
                        {{ paginateLinks($deposits) }}
                    @endif
                </div>
            </div>
        </div>

        {{-- Details MODAL --}}
        <div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Details')</h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal">
                            <i class="la la-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group userData mb-2">
                        </ul>
                        <div class="feedback"></div>
                    </div>
                </div>
            </div>
        </div>
            </section>
        </div>
    </div>
@endsection





@push('script')
    <script>
        (function($) {
            "use strict";
            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');

                var userData = $(this).data('info');
                var html = '';
                if (userData) {
                    userData.forEach(element => {
                        if (element.type != 'file') {
                            html += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>${element.name}</span>
                                <span">${element.value}</span>
                            </li>`;
                        } else {
                            html += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>${element.name}</span>
                                <span"><a href="${element.value}"><i class="fa-regular fa-file"></i> @lang('Attachment')</a></span>
                            </li>`;
                        }
                    });
                }

                modal.find('.userData').html(html);

                if ($(this).data('admin_feedback') != undefined) {
                    var adminFeedback = `
                        <div class="my-3">
                            <strong>@lang('Admin Feedback')</strong>
                            <p>${$(this).data('admin_feedback')}</p>
                        </div>
                    `;
                } else {
                    var adminFeedback = '';
                }

                modal.find('.feedback').html(adminFeedback);


                modal.modal('show');
            });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title], [data-title], [data-bs-title]'))
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

        })(jQuery);
    </script>
@endpush
