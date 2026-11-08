@extends('toapp_admin.layouts.app')

@section('content')
@php
    $depositStatus = ['Initiated', 'Success', 'Pending', 'Rejected'][$deposit->status] ?? null;
@endphp
<section class="ta-grid ta-grid-main">
    <article class="ta-panel">
        <div class="ta-panel-head"><div><span class="ta-kicker">@lang('Deposit')</span><h2>{{ $deposit->trx }}</h2></div><a class="ta-row-action" href="{{ route('toapp.admin.deposits.index') }}">@lang('Back')</a></div>
        <div class="ta-detail-list">
            <div><span>@lang('User')</span><strong>{{ optional($deposit->user)->username ?? __('Unknown') }}</strong></div>
            <div><span>@lang('Amount')</span><strong>{{ number_format((float) $deposit->amount, 2) }}</strong></div>
            <div><span>@lang('Charge')</span><strong>{{ number_format((float) $deposit->charge, 2) }}</strong></div>
            <div><span>@lang('Final Amount')</span><strong>{{ number_format((float) $deposit->final_amount, 2) }} {{ $deposit->method_currency }}</strong></div>
            <div><span>@lang('Status')</span><strong>{{ $depositStatus ? __($depositStatus) : $deposit->status }}</strong></div>
            <div><span>@lang('Feedback')</span><strong>{{ $deposit->admin_feedback ?: '-' }}</strong></div>
        </div>
    </article>
    <article class="ta-panel">
        <div class="ta-panel-head"><div><span class="ta-kicker">@lang('Decision')</span><h2>@lang('Review Request')</h2></div><i class="las la-clipboard-check"></i></div>
        <div class="ta-form-stack">
            @if($deposit->status == \App\Constants\Status::PAYMENT_PENDING)
                <form method="POST" action="{{ route('toapp.admin.deposits.approve', $deposit) }}">@csrf<button class="ta-primary-btn" type="submit">@lang('Approve Deposit')</button></form>
                <form method="POST" action="{{ route('toapp.admin.deposits.reject', $deposit) }}">
                    @csrf
                    <label class="ta-field"><span>@lang('Reject Message')</span><textarea name="message" rows="4" required></textarea></label>
                    <button class="ta-danger-btn" type="submit">@lang('Reject Deposit')</button>
                </form>
            @else
                <div class="ta-empty">@lang('This deposit is already finalized.')</div>
            @endif
        </div>
    </article>
</section>
@endsection
