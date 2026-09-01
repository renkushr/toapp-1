@extends('toapp_agent.layouts.app')

@section('content')
<div class="row row-cards mb-4">
    <div class="col-md-8">
        <div>
            <h2 class="mb-1">Performance report</h2>
            <p class="text-secondary mb-0">Your clients, activity, and sales summary.</p>
        </div>
    </div>
    <div class="col-md-4">
        <form method="GET" class="d-flex gap-2 align-items-end justify-content-md-end">
            <div>
                <label class="form-label">Period</label>
                <select class="form-select form-select-sm" name="days">
                    <option value="7" @selected($days === 7)>Last 7 days</option>
                    <option value="30" @selected($days === 30)>Last 30 days</option>
                    <option value="90" @selected($days === 90)>Last 90 days</option>
                    <option value="0" @selected($days === 0)>All time</option>
                </select>
            </div>
            <button class="btn btn-primary btn-sm" type="submit">Apply</button>
        </form>
    </div>
</div>

<div class="row row-deck row-cards mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-users"></i></span>
                <div class="subheader">Assigned clients</div>
                <div class="h1 mb-0">{{ $metrics['clients_total'] }}</div>
                <div class="text-secondary">{{ $metrics['clients_active'] }} active</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-login"></i></span>
                <div class="subheader">Client logins</div>
                <div class="h1 mb-0">{{ $metrics['logins_count'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-currency-dollar"></i></span>
                <div class="subheader">Investment sales</div>
                <div class="h1 mb-0">{{ showAmount($metrics['investment_sales_amount']) }}</div>
                <div class="text-secondary">{{ $metrics['investment_sales_count'] }} deals</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-file-certificate"></i></span>
                <div class="subheader">Insurance confirmed</div>
                <div class="h1 mb-0">{{ showAmount($metrics['insurance_confirmed_annual']) }}</div>
                <div class="text-secondary">{{ $metrics['insurance_confirmed_count'] }} policies</div>
            </div>
        </div>
    </div>
</div>

<div class="row row-cards mb-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Sales</h3></div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-7">Running products</dt>
                    <dd class="col-5 text-end">{{ $metrics['active_products'] }}</dd>
                    <dt class="col-7">Investment approved/running</dt>
                    <dd class="col-5 text-end">{{ showAmount($metrics['investment_sales_amount']) }}</dd>
                    <dt class="col-7">Investment pending</dt>
                    <dd class="col-5 text-end">{{ $metrics['investment_pending_count'] }} · {{ showAmount($metrics['investment_pending_amount']) }}</dd>
                    <dt class="col-7">Insurance confirmed (annual)</dt>
                    <dd class="col-5 text-end">{{ showAmount($metrics['insurance_confirmed_annual']) }}</dd>
                    <dt class="col-7">Insurance in pipeline</dt>
                    <dd class="col-5 text-end">{{ $metrics['insurance_pipeline_count'] }}</dd>
                    <dt class="col-7">Agent fee ({{ $metrics['agent_fee_percent'] }}%) <span class="badge bg-secondary-lt ms-1">set by admin</span></dt>
                    <dd class="col-5 text-end fw-bold">{{ showAmount($metrics['platform_fee_amount']) }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Activity</h3></div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-7">Approved clients</dt>
                    <dd class="col-5 text-end">{{ $metrics['clients_approved'] }}</dd>
                    <dt class="col-7">Conversations</dt>
                    <dd class="col-5 text-end">{{ $metrics['conversations_count'] }}</dd>
                    <dt class="col-7">Messages from clients</dt>
                    <dd class="col-5 text-end">{{ $metrics['chat_messages_user'] }}</dd>
                    <dt class="col-7">Your messages sent</dt>
                    <dd class="col-5 text-end">{{ $metrics['chat_messages_agent'] }}</dd>
                    <dt class="col-7">Client logins</dt>
                    <dd class="col-5 text-end">{{ $metrics['logins_count'] }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Quick links</h3>
    </div>
    <div class="card-body d-flex flex-wrap gap-2">
        <a href="{{ route('toapp.agent.users.index') }}" class="btn btn-outline-primary btn-sm">View clients</a>
        <a href="{{ route('toapp.agent.chat.index') }}" class="btn btn-outline-primary btn-sm">Open chat</a>
        <a href="{{ route('toapp.agent.applications.index') }}" class="btn btn-outline-primary btn-sm">Applications</a>
    </div>
</div>
@endsection
