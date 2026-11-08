@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="app-page">
        <div class="app-container">
            <div class="app-topbar">
                <a class="app-icon-btn" href="{{ route('user.plans') }}" aria-label="@lang('Back to plans')">
                    <i class="las la-arrow-left"></i>
                </a>
                <a class="app-brand" href="{{ route('user.home') }}">
                    <img src="{{ siteLogo() }}" alt="To-App">
                    <span>@lang('Investments')</span>
                </a>
                <button type="button" class="app-icon-btn showFilterBtn" aria-label="@lang('Filter')">
                    <i class="las la-filter"></i>
                </button>
            </div>

            <section class="app-hero">
                <span class="app-eyebrow">
                    <i class="las la-chart-area"></i>
                    @lang('Investment log')
                </span>
                <h1>@lang('Track every plan you started.')</h1>
                <p>@lang('Filter by transaction, return type, or status so users can quickly understand what is running and what is completed.')</p>
            </section>

            <div class="responsive-filter-card my-3 p-3">
                <form action="">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="fw-bold mb-2">@lang('Transaction number')</label>
                            <input type="search" name="search" value="{{ request()->search }}" class="form-control" placeholder="@lang('Search by trx')">
                        </div>
                        <div class="col-md-3 select2-parent">
                            <label class="fw-bold mb-2">@lang('Interest type')</label>
                            <select name="interest_type" class="form-control select2-basic" data-minimum-results-for-search="-1">
                                <option value="">@lang('All')</option>
                                <option value="1" @selected(request()->interest_type == 1)>@lang('Percent')</option>
                                <option value="2" @selected(request()->interest_type == 2)>@lang('Fixed')</option>
                            </select>
                        </div>
                        <div class="col-md-3 select2-parent">
                            <label class="fw-bold mb-2">@lang('Status')</label>
                            <select name="status" class="form-control select2-basic" data-minimum-results-for-search="-1">
                                <option value="">@lang('All')</option>
                                <option value="0" @selected(request()->status === '0')>@lang('Pending')</option>
                                <option value="2" @selected(request()->status == 2)>@lang('Running')</option>
                                <option value="1" @selected(request()->status == 1)>@lang('Completed')</option>
                                <option value="3" @selected(request()->status == 3)>@lang('Rejected')</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="app-btn w-100" style="color:#fff; background: linear-gradient(135deg, #1268f3, #13c8d6);">
                                <i class="las la-filter"></i>
                                @lang('Apply')
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="app-section-title">
                <div>
                    <h2>@lang('Investment history')</h2>
                    <p>@lang('Every row is mobile-friendly and keeps the important status visible.')</p>
                </div>
                <a class="app-link" href="{{ route('user.plans') }}">
                    @lang('Add plan')
                    <i class="las la-arrow-right"></i>
                </a>
            </div>

            <div class="app-table-shell table-responsive--md">
                <table class="table custom--table">
                    <thead>
                        <tr>
                            <th>@lang('Trx')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Per Return Interest')</th>
                            <th>@lang('Interest Type')</th>
                            <th>@lang('Total Return')</th>
                            <th>@lang('Get Return')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Next Return Date')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($investments as $investment)
                            <tr>
                                <td>{{ $investment->trx }}</td>
                                <td>{{ showAmount($investment->amount) }}</td>
                                <td>{{ showAmount($investment->interest_amount) }}</td>
                                <td>{{ $investment->interest_type == 1 ? __('Percent') : __('Fixed') }}</td>
                                <td>{{ $investment->total_return }} @lang('Times')</td>
                                <td>{{ $investment->total_paid }} @lang('Times')</td>
                                <td>@php echo $investment->statusBadge @endphp</td>
                                <td>
                                    @if($investment->status == 0)
                                        @lang('Waiting admin approval')
                                    @elseif($investment->status == 3)
                                        {{ $investment->rejection_reason ?: __('Rejected') }}
                                    @else
                                        {{ showDateTime($investment->next_return_date) }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center text-muted py-4">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($investments->hasPages())
                <div class="pt-3">
                    {{ paginateLinks($investments) }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush
