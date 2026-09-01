@extends('toapp_admin.layouts.app')

@section('content')
<div class="row row-deck row-cards mb-4">
    @foreach([
        ['Total paid', number_format((float) $stats['total_paid'], 2), 'Filtered referral fee value'],
        ['Payout entries', number_format($stats['commission_count']), 'Ledger records'],
        ['Earners', number_format($stats['earner_count']), 'Members who received referral fee'],
        ['Source buyers', number_format($stats['source_count']), 'Members who triggered payouts'],
    ] as [$label, $value, $hint])
        <div class="col-sm-6 col-lg-3">
            <div class="card portal-stat-card">
                <div class="card-body">
                    <div class="subheader">{{ $label }}</div>
                    <div class="h2 mb-1">{{ $value }}</div>
                    <div class="text-secondary">{{ $hint }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="card mb-4">
    @include('partials.portal_list_header', [
        'title' => 'Referral rules',
        'toolbarView' => 'partials.portal_list_toolbar_referrals',
    ])
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead><tr><th>Package</th><th>Level</th><th>Percent</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse($rules as $rule)
                    <tr>
                        <td><div class="fw-bold">{{ optional($rule->plan)->name ?? 'Global fallback' }}</div><div class="text-secondary">{{ $rule->commission_type }}</div></td>
                        <td>Level {{ $rule->level }}</td>
                        <td>{{ number_format((float) $rule->percent, 2) }}%</td>
                        <td><span class="badge {{ $rule->status ? 'bg-success-lt' : 'bg-secondary-lt' }}">{{ $rule->status ? 'Enabled' : 'Disabled' }}</span></td>
                        <td>
                            <form method="POST" action="{{ route('toapp.admin.referrals.rules.destroy', $rule) }}" onsubmit="return confirm('Delete this rule?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-secondary py-4">No referral rules yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header portal-list-header">
        <h3 class="card-title">Referral fee payouts</h3>
    </div>
    <form class="portal-filter-bar" method="GET">
        <div class="portal-filter-field">
            <label class="form-label">Search</label>
            <input class="form-control form-control-sm" type="search" name="search" value="{{ request('search') }}" placeholder="trx, earner, source">
        </div>
        <div class="portal-filter-field">
            <label class="form-label">Package</label>
            <select class="form-select form-select-sm" name="plan_id">
                <option value="">Any</option>
                <option value="global" @selected(request('plan_id') === 'global')>Global / none</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" @selected((string) request('plan_id') === (string) $plan->id)>{{ $plan->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="portal-filter-field">
            <label class="form-label">Level</label>
            <input class="form-control form-control-sm" type="number" name="level" min="1" value="{{ request('level') }}" placeholder="Any">
        </div>
        <div class="portal-filter-actions">
            <button class="btn btn-primary" type="submit"><i class="ti ti-search me-1"></i> Search</button>
            <a class="btn btn-outline-secondary" href="{{ route('toapp.admin.referrals.export', request()->query()) }}"><i class="ti ti-file-export me-1"></i> Export CSV</a>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead><tr><th>TRX</th><th>Earner</th><th>From member</th><th>Package</th><th>Level</th><th>Amount</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($commissions as $commission)
                    <tr>
                        <td><div class="fw-bold">{{ $commission->trx ?? '-' }}</div><div class="text-secondary">#{{ $commission->id }}</div></td>
                        <td><div class="fw-bold">{{ optional($commission->earner)->username ?? 'Unknown' }}</div><div class="text-secondary">{{ optional($commission->earner)->email }}</div></td>
                        <td><div class="fw-bold">{{ optional($commission->sourceUser)->username ?? 'Unknown' }}</div><div class="text-secondary">{{ optional($commission->sourceUser)->email }}</div></td>
                        <td>{{ optional($commission->plan)->name ?? '-' }}</td>
                        <td>Level {{ $commission->level }}<div class="text-secondary">{{ number_format((float) $commission->percent, 2) }}%</div></td>
                        <td class="text-success fw-bold">+{{ number_format((float) $commission->amount, 2) }}<div class="text-secondary fw-normal">base {{ number_format((float) $commission->base_amount, 2) }}</div></td>
                        <td class="text-secondary">{{ optional($commission->paid_at ?? $commission->created_at)->format('M d, Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-secondary py-4">No referral fees paid yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($commissions->hasPages())
        <div class="card-footer">{{ $commissions->links() }}</div>
    @endif
</div>

@include('partials.portal_help_modal', [
    'id' => 'referralHelpModal',
    'title' => 'How referral fee works',
    'partial' => 'toapp_admin.referrals.partials.help',
])
@endsection
