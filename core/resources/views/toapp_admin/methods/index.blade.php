@extends('toapp_admin.layouts.app')

@section('content')
<div class="row row-cards">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">@lang('Deposit gateways')</h3></div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead><tr><th>@lang('Gateway')</th><th>@lang('Status')</th><th></th></tr></thead>
                    <tbody>
                        @forelse($depositGateways as $gateway)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $gateway->name }}</div>
                                    <div class="text-secondary">{{ $gateway->alias }} · @lang('code') {{ $gateway->code }}</div>
                                </td>
                                <td><span class="badge {{ $gateway->status ? 'bg-success-lt' : 'bg-danger-lt' }}">{{ $gateway->status ? __('Enabled') : __('Disabled') }}</span></td>
                                <td><a href="{{ route('toapp.admin.methods.deposit.edit', $gateway) }}" class="btn btn-sm">@lang('Edit')</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-secondary text-center">@lang('No deposit gateways.')</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">@lang('Withdrawal methods')</h3></div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead><tr><th>@lang('Method')</th><th>@lang('Status')</th><th></th></tr></thead>
                    <tbody>
                        @forelse($withdrawMethods as $method)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $method->name }}</div>
                                    <div class="text-secondary">{{ $method->currency }} · @lang('rate') {{ number_format((float) $method->rate, 4) }}</div>
                                </td>
                                <td><span class="badge {{ $method->status ? 'bg-success-lt' : 'bg-danger-lt' }}">{{ $method->status ? __('Enabled') : __('Disabled') }}</span></td>
                                <td><a href="{{ route('toapp.admin.methods.withdraw.edit', $method) }}" class="btn btn-sm">@lang('Edit')</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-secondary text-center">@lang('No withdrawal methods.')</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
