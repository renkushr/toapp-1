@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="app-page">
        <div class="app-container">
            <div class="app-topbar">
                <a class="app-icon-btn" href="{{ route('user.withdraw.index') }}" aria-label="@lang('Back to withdraw')">
                    <i class="las la-arrow-left"></i>
                </a>
                <a class="app-brand" href="{{ route('user.home') }}">
                    <img src="{{ siteLogo() }}" alt="To-App">
                    <span>@lang('Withdrawals')</span>
                </a>
                <button type="button" class="app-icon-btn showFilterBtn" aria-label="@lang('Search')">
                    <i class="las la-search"></i>
                </button>
            </div>

            <section class="app-hero">
                <span class="app-eyebrow">
                    <i class="las la-history"></i>
                    @lang('Withdrawal history')
                </span>
                <h1>@lang('Follow every payout request.')</h1>
                <p>@lang('See requested amounts, charges, conversion values, and admin status in one place.')</p>

                <div class="app-pills">
                    <span class="app-pill">
                        <i class="las la-wallet"></i>
                        @lang('Available') {{ showAmount(auth()->user()->balance) }}
                    </span>
                </div>
            </section>

            <div class="responsive-filter-card my-3 p-3">
                <form action="">
                    <div class="row g-3">
                        <div class="col-md-10">
                            <label class="fw-bold mb-2">@lang('Search transaction')</label>
                            <input type="search" name="search" value="{{ request()->search }}" class="form-control" placeholder="@lang('Search by trx')">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="app-btn app-btn-primary w-100" aria-label="@lang('Search')">
                                <i class="las la-search"></i>
                                @lang('Search')
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="app-section-title">
                <div>
                    <h2>@lang('Requests')</h2>
                    <p>@lang('Tap details to inspect submitted destination information.')</p>
                </div>
                <a class="app-link" href="{{ route('user.withdraw.index') }}">
                    @lang('New withdrawal')
                    <i class="las la-arrow-right"></i>
                </a>
            </div>

            <div class="app-list-card">
                @forelse($withdraws as $withdraw)
                    <div class="app-list-item withdrawal-log-item">
                        <span class="app-list-icon"><i class="las la-money-bill-wave"></i></span>
                        <div class="app-list-body">
                            <strong>{{ __(@$withdraw->method->name) }} · {{ $withdraw->trx }}</strong>
                            <span>{{ showDateTime($withdraw->created_at) }} · {{ diffForHumans($withdraw->created_at) }}</span>
                            <span>
                                @lang('Requested') {{ showAmount($withdraw->amount) }}
                                · @lang('Charge') {{ showAmount($withdraw->charge) }}
                            </span>
                            <span>
                                @lang('Receives') {{ showAmount($withdraw->final_amount) }} {{ __($withdraw->currency) }}
                            </span>
                        </div>
                        <div class="withdrawal-log-action">
                            @php echo $withdraw->statusBadge @endphp
                            <button class="app-icon-btn detailBtn mt-2" type="button" data-user_data="{{ json_encode($withdraw->withdraw_information) }}"
                                @if ($withdraw->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $withdraw->admin_feedback }}" @endif aria-label="@lang('View details')">
                                <i class="las la-eye"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="app-empty-state">
                        <i class="las la-money-bill-wave d-block fs-1 mb-2"></i>
                        @lang($emptyMessage)
                    </div>
                @endforelse
            </div>

            @if ($withdraws->hasPages())
                <div class="pt-3">
                    {{ $withdraws->links() }}
                </div>
            @endif
        </div>

        <div id="detailModal" class="modal fade app-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Withdrawal details')</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="@lang('Close')"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group userData mb-2"></ul>
                        <div class="feedback"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .withdrawal-log-item {
            align-items: flex-start;
        }

        .withdrawal-log-action {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            flex: 0 0 auto;
        }

        @media (max-width: 575px) {
            .withdrawal-log-item {
                flex-wrap: wrap;
            }

            .withdrawal-log-action {
                width: 100%;
                align-items: flex-start;
                margin-left: 54px;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');
                var userData = $(this).data('user_data') || [];
                var html = '';

                if (userData.length) {
                    userData.forEach(element => {
                        if (element.type != 'file') {
                            html += `
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>${element.name}</span>
                                    <strong>${element.value}</strong>
                                </li>`;
                        }
                    });
                } else {
                    html = `<li class="list-group-item text-muted">@lang('No extra details were submitted.')</li>`;
                }

                modal.find('.userData').html(html);

                if ($(this).data('admin_feedback') != undefined) {
                    modal.find('.feedback').html(`
                        <div class="app-card mt-3">
                            <strong>@lang('Admin Feedback')</strong>
                            <p class="mb-0 mt-2">${$(this).data('admin_feedback')}</p>
                        </div>
                    `);
                } else {
                    modal.find('.feedback').html('');
                }

                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
