@extends('toapp_admin.layouts.app')

@section('content')
@include('toapp_admin.reports.partials.tabs')

<div class="mb-3">
    <a href="{{ route('toapp.admin.reports.agents', ['days' => $days]) }}" class="btn btn-ghost-secondary btn-sm">
        <i class="ti ti-arrow-left me-1"></i> @lang('Back to all agents')
    </a>
</div>

<div class="row row-deck row-cards mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h2 class="mb-1">{{ $agent->name }}</h2>
                <div class="text-secondary">{{ $agent->license_no }} · {{ $agent->email }} · {{ $agent->username }}</div>
                <div class="mt-2">
                    <span class="badge {{ $agent->status ? 'bg-success-lt' : 'bg-secondary-lt' }}">{{ $agent->status ? __('Active') : __('Inactive') }}</span>
                    <span class="badge bg-primary-lt">@lang('Agent fee') {{ $agent->feePercentLabel() }}</span>
                </div>
                <p class="text-secondary mt-3 mb-0">@lang('Coverage'): {{ $agent->zipCodeList() ?: __('No ZIP codes assigned.') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="d-flex gap-2 align-items-end">
                    <div class="flex-fill">
                        <label class="form-label">@lang('Activity period')</label>
                        <select class="form-select form-select-sm" name="days">
                            <option value="7" @selected($days === 7)>@lang('Last 7 days')</option>
                            <option value="30" @selected($days === 30)>@lang('Last 30 days')</option>
                            <option value="90" @selected($days === 90)>@lang('Last 90 days')</option>
                            <option value="0" @selected($days === 0)>@lang('All time')</option>
                        </select>
                    </div>
                    <button class="btn btn-primary btn-sm" type="submit">@lang('Apply')</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row row-deck row-cards mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-users"></i></span>
                <div class="subheader">@lang('Clients')</div>
                <div class="h1 mb-0">{{ $metrics['clients_total'] }}</div>
                <div class="text-secondary">{{ $metrics['clients_active'] }} @lang('active') · {{ $metrics['clients_approved'] }} @lang('approved')</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-login"></i></span>
                <div class="subheader">@lang('Client logins')</div>
                <div class="h1 mb-0">{{ $metrics['logins_count'] }}</div>
                <div class="text-secondary">@lang('in selected period')</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-currency-dollar"></i></span>
                <div class="subheader">@lang('Investment sales')</div>
                <div class="h1 mb-0">{{ showAmount($metrics['investment_sales_amount']) }}</div>
                <div class="text-secondary">{{ $metrics['investment_sales_count'] }} @lang('deals')</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-file-certificate"></i></span>
                <div class="subheader">@lang('Insurance confirmed')</div>
                <div class="h1 mb-0">{{ showAmount($metrics['insurance_confirmed_annual']) }}</div>
                <div class="text-secondary">{{ $metrics['insurance_confirmed_count'] }} @lang('policies')</div>
            </div>
        </div>
    </div>
</div>

<div class="row row-cards">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">@lang('Sales breakdown')</h3></div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-7">@lang('Running products')</dt>
                    <dd class="col-5 text-end">{{ $metrics['active_products'] }}</dd>
                    <dt class="col-7">@lang('Investment approved/running')</dt>
                    <dd class="col-5 text-end">{{ $metrics['investment_sales_count'] }} · {{ showAmount($metrics['investment_sales_amount']) }}</dd>
                    <dt class="col-7">@lang('Investment pending')</dt>
                    <dd class="col-5 text-end">{{ $metrics['investment_pending_count'] }} · {{ showAmount($metrics['investment_pending_amount']) }}</dd>
                    <dt class="col-7">@lang('Insurance confirmed (annual)')</dt>
                    <dd class="col-5 text-end">{{ $metrics['insurance_confirmed_count'] }} · {{ showAmount($metrics['insurance_confirmed_annual']) }}</dd>
                    <dt class="col-7">@lang('Insurance pipeline')</dt>
                    <dd class="col-5 text-end">{{ $metrics['insurance_pipeline_count'] }}</dd>
                    <dt class="col-7">@lang('Agent fee') ({{ $agent->feePercentLabel() }})</dt>
                    <dd class="col-5 text-end fw-bold">{{ showAmount($metrics['platform_fee_amount']) }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">@lang('Engagement')</h3></div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-7">@lang('Conversations')</dt>
                    <dd class="col-5 text-end">{{ $metrics['conversations_count'] }}</dd>
                    <dt class="col-7">@lang('Messages from clients')</dt>
                    <dd class="col-5 text-end">{{ $metrics['chat_messages_user'] }}</dd>
                    <dt class="col-7">@lang('Messages from agent')</dt>
                    <dd class="col-5 text-end">{{ $metrics['chat_messages_agent'] }}</dd>
                    <dt class="col-7">@lang('Client logins')</dt>
                    <dd class="col-5 text-end">{{ $metrics['logins_count'] }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
