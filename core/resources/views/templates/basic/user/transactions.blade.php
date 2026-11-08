@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="app-page">
        <div class="app-container">
            <div class="app-topbar">
                <a class="app-icon-btn" href="{{ route('user.setting') }}" aria-label="@lang('Back to settings')">
                    <i class="las la-arrow-left"></i>
                </a>
                <a class="app-brand" href="{{ route('user.home') }}">
                    <img src="{{ siteLogo() }}" alt="To-App">
                    <span>@lang('Transactions')</span>
                </a>
                <button type="button" class="app-icon-btn showFilterBtn" aria-label="@lang('Filter')">
                    <i class="las la-filter"></i>
                </button>
            </div>

            <section class="app-hero">
                <span class="app-eyebrow">
                    <i class="las la-receipt"></i>
                    @lang('Transaction history')
                </span>
                <h1>@lang('Review every balance movement.')</h1>
                <p>@lang('Search by reference, filter by type, and keep credits and debits easy to scan.')</p>
            </section>

            <div class="responsive-filter-card my-3 p-3">
                <form action="">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="fw-bold mb-2">@lang('Search transaction')</label>
                            <input type="search" name="search" value="{{ request()->search }}" class="form-control" placeholder="@lang('Search by trx')">
                        </div>

                        <div class="col-md-3">
                            <label class="fw-bold mb-2">@lang('Type')</label>
                            <select name="trx_type" class="form-control">
                                <option value="">@lang('All')</option>
                                <option value="+" @selected(request()->trx_type === '+')>@lang('Credit')</option>
                                <option value="-" @selected(request()->trx_type === '-')>@lang('Debit')</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="fw-bold mb-2">@lang('Category')</label>
                            <select name="remark" class="form-control">
                                <option value="">@lang('All')</option>
                                @foreach ($remarks as $remark)
                                    <option value="{{ $remark->remark }}" @selected(request()->remark === $remark->remark)>{{ ucfirst($remark->remark) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-1 d-flex align-items-end">
                            <button class="app-btn w-100" style="color:#fff; background: linear-gradient(135deg, #1268f3, #13c8d6);" aria-label="@lang('Apply filters')">
                                <i class="las la-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="app-section-title">
                <div>
                    <h2>@lang('Activity')</h2>
                    <p>@lang('Reference numbers and post-balance are kept visible for support.')</p>
                </div>
            </div>

            <div class="app-list-card">
                @forelse($transactions as $trx)
                    <div class="app-list-item">
                        <span class="app-list-icon">
                            <i class="las {{ $trx->trx_type == '+' ? 'la-arrow-down' : 'la-arrow-up' }}"></i>
                        </span>
                        <div class="app-list-body">
                            <strong>{{ $trx->details }}</strong>
                            <span>{{ showDateTime($trx->created_at, 'M d, Y h:i A') }} · Ref: {{ $trx->trx }}</span>
                            <span>@lang('Balance after transaction'): {{ showAmount($trx->post_balance) }}</span>
                        </div>
                        <div class="app-amount {{ $trx->trx_type == '+' ? 'is-plus' : 'is-minus' }}">
                            {{ $trx->trx_type }}{{ showAmount($trx->amount) }}
                        </div>
                    </div>
                @empty
                    <div class="app-empty-state">
                        <i class="las la-receipt d-block fs-1 mb-2"></i>
                        @lang('No transactions found.')
                    </div>
                @endforelse
            </div>

            @if ($transactions->hasPages())
                <div class="pt-3">
                    {{ paginateLinks($transactions) }}
                </div>
            @endif
        </div>
    </div>
@endsection
