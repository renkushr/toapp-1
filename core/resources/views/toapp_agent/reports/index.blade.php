@extends('toapp_agent.layouts.app')

@section('content')
<div class="row row-cards mb-4">
    <div class="col-md-8">
        <div>
            <h2 class="mb-1">@lang('Performance report')</h2>
            <p class="text-secondary mb-0">@lang('Clients, chat activity, pipeline, and sales at a glance.')</p>
        </div>
    </div>
    <div class="col-md-4">
        <form method="GET" class="d-flex gap-2 align-items-end justify-content-md-end flex-wrap">
            <div>
                <label class="form-label">@lang('Year')</label>
                <select class="form-select form-select-sm" name="year" id="report-year">
                    <option value="" @selected(empty($year))>@lang('Use period')</option>
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" @selected((int) ($year ?? 0) === (int) $y)>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">@lang('Period')</label>
                <select class="form-select form-select-sm" name="days" id="report-days" @disabled(!empty($year))>
                    <option value="7" @selected(($days ?? 30) === 7)>@lang('Last 7 days')</option>
                    <option value="30" @selected(($days ?? 30) === 30)>@lang('Last 30 days')</option>
                    <option value="90" @selected(($days ?? 30) === 90)>@lang('Last 90 days')</option>
                    <option value="0" @selected(($days ?? 30) === 0 && empty($year))>@lang('All time')</option>
                </select>
            </div>
            <button class="btn btn-primary btn-sm" type="submit">@lang('Apply')</button>
        </form>
    </div>
</div>

<div class="row row-deck row-cards mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-users"></i></span>
                <div class="subheader">@lang('Assigned clients')</div>
                <div class="h1 mb-0">{{ $metrics['clients_total'] }}</div>
                <div class="text-secondary">{{ $metrics['clients_active'] }} @lang('active')</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card portal-stat-card">
            <div class="card-body">
                <span class="portal-stat-icon"><i class="ti ti-login"></i></span>
                <div class="subheader">@lang('Client logins')</div>
                <div class="h1 mb-0">{{ $metrics['logins_count'] }}</div>
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

<div class="row row-cards mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Activity trend')</h3>
                <div class="card-actions">
                    <span class="text-secondary small">
                        {{ $charts['trend_label'] ?? ($period['label'] ?? '') }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="agent-report-chart-wrap">
                    <canvas id="agentTrendChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row row-cards mb-4">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header"><h3 class="card-title">@lang('Insurance pipeline')</h3></div>
            <div class="card-body">
                <div class="agent-report-chart-wrap agent-report-chart-wrap--donut">
                    <canvas id="agentInsuranceStatusChart"></canvas>
                </div>
                @if(empty($charts['insurance_status']['values']))
                    <p class="text-secondary text-center mb-0 mt-2">@lang('No applications in this period.')</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header"><h3 class="card-title">@lang('Sales mix')</h3></div>
            <div class="card-body">
                <div class="agent-report-chart-wrap agent-report-chart-wrap--donut">
                    <canvas id="agentSalesMixChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header"><h3 class="card-title">@lang('Client status')</h3></div>
            <div class="card-body">
                <div class="agent-report-chart-wrap agent-report-chart-wrap--donut">
                    <canvas id="agentClientsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row row-cards mb-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">@lang('Sales')</h3></div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-7">@lang('Running products')</dt>
                    <dd class="col-5 text-end">{{ $metrics['active_products'] }}</dd>
                    <dt class="col-7">@lang('Investment approved/running')</dt>
                    <dd class="col-5 text-end">{{ showAmount($metrics['investment_sales_amount']) }}</dd>
                    <dt class="col-7">@lang('Investment pending')</dt>
                    <dd class="col-5 text-end">{{ $metrics['investment_pending_count'] }} · {{ showAmount($metrics['investment_pending_amount']) }}</dd>
                    <dt class="col-7">@lang('Insurance confirmed (annual)')</dt>
                    <dd class="col-5 text-end">{{ showAmount($metrics['insurance_confirmed_annual']) }}</dd>
                    <dt class="col-7">@lang('Insurance in pipeline')</dt>
                    <dd class="col-5 text-end">{{ $metrics['insurance_pipeline_count'] }}</dd>
                    <dt class="col-7">@lang('Agent fee') ({{ $metrics['agent_fee_percent'] }}%) <span class="badge bg-secondary-lt ms-1">@lang('set by admin')</span></dt>
                    <dd class="col-5 text-end fw-bold">{{ showAmount($metrics['platform_fee_amount']) }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">@lang('Activity')</h3></div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-7">@lang('Approved clients')</dt>
                    <dd class="col-5 text-end">{{ $metrics['clients_approved'] }}</dd>
                    <dt class="col-7">@lang('Conversations')</dt>
                    <dd class="col-5 text-end">{{ $metrics['conversations_count'] }}</dd>
                    <dt class="col-7">@lang('Messages from clients')</dt>
                    <dd class="col-5 text-end">{{ $metrics['chat_messages_user'] }}</dd>
                    <dt class="col-7">@lang('Your messages sent')</dt>
                    <dd class="col-5 text-end">{{ $metrics['chat_messages_agent'] }}</dd>
                    <dt class="col-7">@lang('Client logins')</dt>
                    <dd class="col-5 text-end">{{ $metrics['logins_count'] }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">@lang('Quick links')</h3>
    </div>
    <div class="card-body d-flex flex-wrap gap-2">
        <a href="{{ route('toapp.agent.users.index') }}" class="btn btn-outline-primary btn-sm">@lang('View clients')</a>
        <a href="{{ route('toapp.agent.chat.index') }}" class="btn btn-outline-primary btn-sm">@lang('Open chat')</a>
        <a href="{{ route('toapp.agent.applications.index') }}" class="btn btn-outline-primary btn-sm">@lang('Applications')</a>
    </div>
</div>
@endsection

@push('styles')
<style>
.agent-report-chart-wrap {
    position: relative;
    width: 100%;
    min-height: 260px;
}
.agent-report-chart-wrap--donut {
    max-width: 280px;
    margin: 0 auto;
    min-height: 240px;
}
</style>
@endpush

@push('script-lib')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js"></script>
@endpush

@push('scripts')
@php
    $agentReportI18n = [
        'clientMessages' => __('Client messages'),
        'clientLogins' => __('Client logins'),
        'insuranceConfirmed' => __('Insurance confirmed'),
        'investmentDeals' => __('Investment deals'),
        'noData' => __('No data'),
        'noApplications' => __('No applications'),
        'noSales' => __('No sales'),
        'noClients' => __('No clients'),
    ];
@endphp
<script>
(function () {
    const charts = @json($charts);
    const i18n = @json($agentReportI18n);
    if (typeof Chart === 'undefined') return;

    const palette = ['#5b5ef7', '#22d3ee', '#f59e0b', '#ef4444', '#10b981', '#8b5cf6', '#64748b'];

    function hasValues(values) {
        return Array.isArray(values) && values.some(function (v) { return Number(v) > 0; });
    }

    const trendCtx = document.getElementById('agentTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: charts.trend.labels,
                datasets: [
                    {
                        label: i18n.clientMessages,
                        data: charts.trend.client_messages,
                        borderColor: '#5b5ef7',
                        backgroundColor: 'rgba(91, 94, 247, 0.12)',
                        tension: 0.35,
                        fill: true,
                        pointRadius: 0,
                        borderWidth: 2,
                    },
                    {
                        label: i18n.clientLogins,
                        data: charts.trend.logins,
                        borderColor: '#22d3ee',
                        backgroundColor: 'transparent',
                        tension: 0.35,
                        pointRadius: 0,
                        borderWidth: 2,
                    },
                    {
                        label: i18n.insuranceConfirmed,
                        data: charts.trend.insurance_confirmed,
                        borderColor: '#10b981',
                        backgroundColor: 'transparent',
                        tension: 0.35,
                        pointRadius: 0,
                        borderWidth: 2,
                    },
                    {
                        label: i18n.investmentDeals,
                        data: charts.trend.investment_deals,
                        borderColor: '#f59e0b',
                        backgroundColor: 'transparent',
                        tension: 0.35,
                        pointRadius: 0,
                        borderWidth: 2,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { position: 'bottom' },
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                },
            },
        });
    }

    function donut(canvasId, labels, values, emptyLabel) {
        const el = document.getElementById(canvasId);
        if (!el) return;

        const dataLabels = hasValues(values) ? labels : [emptyLabel || i18n.noData];
        const dataValues = hasValues(values) ? values : [1];
        const colors = hasValues(values) ? palette : ['#e2e8f0'];

        new Chart(el, {
            type: 'doughnut',
            data: {
                labels: dataLabels,
                datasets: [{
                    data: dataValues,
                    backgroundColor: colors.slice(0, dataLabels.length),
                    borderWidth: 0,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, padding: 14 },
                    },
                    tooltip: {
                        enabled: hasValues(values),
                    },
                },
            },
        });
    }

    donut('agentInsuranceStatusChart', charts.insurance_status.labels, charts.insurance_status.values, i18n.noApplications);
    donut('agentSalesMixChart', charts.sales_mix.labels, charts.sales_mix.values, i18n.noSales);
    donut('agentClientsChart', charts.clients.labels, charts.clients.values, i18n.noClients);

    const yearSelect = document.getElementById('report-year');
    const daysSelect = document.getElementById('report-days');
    function syncPeriodControls() {
        if (!yearSelect || !daysSelect) return;
        const hasYear = !!yearSelect.value;
        daysSelect.disabled = hasYear;
    }
    yearSelect?.addEventListener('change', syncPeriodControls);
    syncPeriodControls();
})();
</script>
@endpush
