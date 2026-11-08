@extends('toapp_admin.layouts.app')

@section('content')
@php
    $withdrawalStatus = [1 => 'Approved', 2 => 'Pending', 3 => 'Rejected'][$withdrawal->status] ?? null;
@endphp
<section class="ta-grid ta-grid-main">
    <article class="ta-panel">
        <div class="ta-panel-head"><div><span class="ta-kicker">@lang('Withdrawal')</span><h2>{{ $withdrawal->trx }}</h2></div><a class="ta-row-action" href="{{ route('toapp.admin.withdrawals.index') }}">@lang('Back')</a></div>
        <div class="ta-detail-list">
            <div><span>@lang('User')</span><strong>{{ optional($withdrawal->user)->username ?? __('Unknown') }}</strong></div>
            <div><span>@lang('Method')</span><strong>{{ optional($withdrawal->method)->name ?? '-' }}</strong></div>
            <div><span>@lang('Amount')</span><strong>{{ number_format((float) $withdrawal->amount, 2) }}</strong></div>
            <div><span>@lang('Charge')</span><strong>{{ number_format((float) $withdrawal->charge, 2) }}</strong></div>
            <div><span>@lang('Receives')</span><strong>{{ number_format((float) $withdrawal->final_amount, 2) }} {{ $withdrawal->currency }}</strong></div>
            <div><span>@lang('Status')</span><strong>{{ $withdrawalStatus ? __($withdrawalStatus) : $withdrawal->status }}</strong></div>
            <div><span>@lang('Feedback')</span><strong>{{ $withdrawal->admin_feedback ?: '-' }}</strong></div>
        </div>
        @if($withdrawal->withdraw_information)
            <div class="ta-json-box">{{ json_encode($withdrawal->withdraw_information, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</div>
        @endif
    </article>
    <article class="ta-panel">
        <div class="ta-panel-head"><div><span class="ta-kicker">@lang('Decision')</span><h2>@lang('Review Request')</h2></div><i class="las la-clipboard-check"></i></div>
        <div class="ta-form-stack">
            @if($withdrawal->status == \App\Constants\Status::PAYMENT_PENDING)
                <form method="POST" action="{{ route('toapp.admin.withdrawals.approve', $withdrawal) }}">
                    @csrf
                    <label class="ta-field"><span>@lang('Admin Details')</span><textarea name="details" rows="4" placeholder="@lang('Payment reference or internal note')"></textarea></label>
                    <button class="ta-primary-btn" type="submit">@lang('Approve Withdrawal')</button>
                </form>
                <form method="POST" action="{{ route('toapp.admin.withdrawals.reject', $withdrawal) }}">
                    @csrf
                    <label class="ta-field"><span>@lang('Reject Reason')</span><textarea name="details" rows="4" required></textarea></label>
                    <button class="ta-danger-btn" type="submit">@lang('Reject & Refund')</button>
                </form>
            @else
                <div class="ta-empty">@lang('This withdrawal is already finalized.')</div>
            @endif
        </div>
    </article>
</section>
@endsection
