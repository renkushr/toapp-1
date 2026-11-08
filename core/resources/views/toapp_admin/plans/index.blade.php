@extends('toapp_admin.layouts.app')

@section('content')
<div class="card mb-3">
    @include('partials.portal_list_header', [
        'title' => __('Product catalog'),
        'toolbarView' => 'partials.portal_list_toolbar_plans',
    ])
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>@lang('Product')</th>
                    <th>@lang('Amount range')</th>
                    <th>@lang('Return')</th>
                    <th>@lang('Pricing')</th>
                    <th>@lang('Eligibility')</th>
                    <th>@lang('Status')</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $plan->name }}</div>
                            <div class="text-secondary">{{ $plan->category ?: __('General') }}</div>
                        </td>
                        <td class="text-secondary">
                            {{ number_format((float) $plan->min_amount, 2) }} – {{ number_format((float) $plan->max_amount, 2) }}
                        </td>
                        <td class="text-secondary">
                            {{ number_format((float) $plan->interest, 2) }}{{ $plan->interest_type == 1 ? '%' : '' }} × {{ $plan->total_return }}
                        </td>
                        <td class="text-secondary">
                            {{ number_format((float) $plan->monthprice, 2) }} / {{ number_format((float) $plan->annualprice, 2) }}
                        </td>
                        <td class="text-secondary">
                            @lang('Age') {{ $plan->agefrom ?? 18 }}–{{ $plan->ageto ?? 99 }}
                            @if($plan->allows_chronic)
                                <span class="badge bg-azure-lt ms-1">@lang('Chronic OK')</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $plan->status ? 'bg-success-lt' : 'bg-secondary-lt' }}">
                                {{ $plan->status ? __('Enabled') : __('Disabled') }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-list flex-nowrap">
                                <a href="{{ route('toapp.admin.plans.edit', $plan) }}" class="btn btn-sm">@lang('Edit')</a>
                                <form method="POST" action="{{ route('toapp.admin.plans.status', $plan) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-secondary" type="submit">
                                        {{ $plan->status ? __('Disable') : __('Enable') }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-secondary py-4">
                            @lang('No products yet.')
                            <a href="{{ route('toapp.admin.plans.create') }}">@lang('Create the first product')</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($plans->hasPages())
        <div class="card-footer">{{ $plans->links() }}</div>
    @endif
</div>
@endsection
