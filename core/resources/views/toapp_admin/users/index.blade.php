@extends('toapp_admin.layouts.app')

@section('content')
@php
    $selectedState = $selectedState ?? '';
    $stateTabs = $stateTabs ?? [];
    $states = $states ?? [];
    $tabQuery = request()->except(['state', 'page']);
@endphp
@if(($unassignedCount ?? 0) > 0)
    <div class="alert alert-warning d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <strong>{{ $unassignedCount }} @lang('customer(s) without agent coverage.')</strong>
            <span class="text-secondary">@lang('These members have a ZIP code but no assigned agent yet.')</span>
        </div>
        <a class="btn btn-sm btn-warning" href="{{ route('toapp.admin.users.index', ['agent' => 'unassigned']) }}">@lang('View list')</a>
    </div>
@endif

<section class="ta-tabs ta-tabs--scroll mb-3">
    <a @class(['active' => $selectedState === '']) href="{{ route('toapp.admin.users.index', $tabQuery) }}">
        @lang('All states')
    </a>
    @foreach($stateTabs as $tab)
        <a @class(['active' => $selectedState === $tab['abbr']]) href="{{ route('toapp.admin.users.index', array_merge($tabQuery, ['state' => $tab['abbr']])) }}">
            {{ $tab['name'] }}
            <span class="badge bg-secondary-lt ms-1">{{ $tab['count'] }}</span>
        </a>
    @endforeach
</section>

<div class="card">
    <div class="card-header portal-list-header">
        <div>
            <h3 class="card-title mb-0">@lang('Users')</h3>
            @if($selectedState !== '')
                <div class="text-secondary small mt-1">
                    @lang('Filtered by state:')
                    <strong>{{ \App\Support\UsZipCatalog::stateName($selectedState) }}</strong>
                    ({{ $selectedState }})
                </div>
            @endif
        </div>
    </div>
    <form class="portal-filter-bar" method="GET">
        @foreach(request()->except(['search', 'status', 'approval', 'kyc', 'agent', 'state', 'page']) as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        <div class="portal-filter-field">
            <label class="form-label">@lang('Search')</label>
            <input type="search" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="@lang('username, email, name, ZIP code')">
        </div>
        <div class="portal-filter-field">
            <label class="form-label">@lang('State')</label>
            <select class="form-select form-select-sm" name="state">
                <option value="">@lang('All states')</option>
                @foreach($states as $state)
                    <option value="{{ $state['abbr'] }}" @selected($selectedState === $state['abbr'])>
                        {{ $state['name'] }} ({{ $state['abbr'] }})
                    </option>
                @endforeach
                @if(collect($stateTabs)->contains(fn ($tab) => $tab['abbr'] === 'OTHER'))
                    <option value="OTHER" @selected($selectedState === 'OTHER')>@lang('Other / unknown')</option>
                @endif
            </select>
        </div>
        <div class="portal-filter-field">
            <label class="form-label">@lang('Status')</label>
            <select class="form-select form-select-sm" name="status">
                <option value="">@lang('Any')</option>
                <option value="1" @selected(request('status') === '1')>@lang('Active')</option>
                <option value="0" @selected(request('status') === '0')>@lang('Banned')</option>
            </select>
        </div>
        <div class="portal-filter-field">
            <label class="form-label">@lang('Approval')</label>
            <select class="form-select form-select-sm" name="approval">
                <option value="">@lang('Any')</option>
                <option value="pending" @selected(request('approval') === 'pending')>@lang('Pending')</option>
                <option value="approved" @selected(request('approval') === 'approved')>@lang('Approved')</option>
                <option value="rejected" @selected(request('approval') === 'rejected')>@lang('Rejected')</option>
            </select>
        </div>
        <div class="portal-filter-field">
            <label class="form-label">@lang('KYC')</label>
            <select class="form-select form-select-sm" name="kyc">
                <option value="">@lang('Any')</option>
                <option value="0" @selected(request('kyc') === '0')>@lang('Unverified')</option>
                <option value="1" @selected(request('kyc') === '1')>@lang('Verified')</option>
                <option value="2" @selected(request('kyc') === '2')>@lang('Pending')</option>
            </select>
        </div>
        <div class="portal-filter-field">
            <label class="form-label">@lang('Agent')</label>
            <select class="form-select form-select-sm" name="agent">
                <option value="">@lang('Any')</option>
                <option value="unassigned" @selected(request('agent') === 'unassigned')>@lang('No agent coverage')</option>
            </select>
        </div>
        <div class="portal-filter-actions">
            <button class="btn btn-primary" type="submit"><i class="ti ti-search me-1"></i> @lang('Search')</button>
            <a class="btn btn-outline-secondary" href="{{ route('toapp.admin.users.export', request()->query()) }}"><i class="ti ti-file-export me-1"></i> @lang('Export CSV')</a>
            @if(request()->hasAny(['search', 'status', 'approval', 'kyc', 'agent', 'state']))
                <a class="btn btn-outline-secondary" href="{{ route('toapp.admin.users.index') }}">@lang('Clear')</a>
            @endif
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>@lang('User')</th>
                    <th>@lang('Contact')</th>
                    <th>@lang('ZIP code')</th>
                    <th>@lang('State')</th>
                    <th>@lang('Balance')</th>
                    <th>@lang('Checks')</th>
                    <th>@lang('Joined')</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    @php
                        $zip = \App\Support\UsZip::normalize((string) $user->zipcode) ?: (string) $user->zipcode;
                        $stateAbbr = $zip ? (\App\Support\UsZipCatalog::stateForZip($zip) ?: 'OTHER') : '';
                        $approvalClass = $user->approval_status === 'approved' ? 'bg-success-lt' : ($user->approval_status === 'rejected' ? 'bg-danger-lt' : 'bg-warning-lt');
                        $approvalLabel = ucfirst($user->approval_status ?? 'approved');
                        $kycLabel = $user->kv == 1 ? __('OK') : ($user->kv == 2 ? __('Pending') : __('No'));
                    @endphp
                    <tr>
                        <td>
                            <div class="fw-bold">{{ trim($user->firstname . ' ' . $user->lastname) ?: $user->username }}</div>
                            <div class="text-secondary">{{ '@' . $user->username }}</div>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $user->email }}</div>
                            <div class="text-secondary">{{ $user->dial_code }}{{ $user->mobile }}</div>
                        </td>
                        <td>
                            @if($zip)
                                <code>{{ $zip }}</code>
                            @else
                                <span class="text-secondary">—</span>
                            @endif
                        </td>
                        <td>
                            @if($stateAbbr)
                                <div class="fw-bold">{{ \App\Support\UsZipCatalog::stateName($stateAbbr) }}</div>
                                <div class="text-secondary">{{ $stateAbbr }}</div>
                            @else
                                <span class="text-secondary">—</span>
                            @endif
                        </td>
                        <td>{{ number_format((float) $user->balance, 2) }}</td>
                        <td>
                            <span class="badge {{ $user->status ? 'bg-success-lt' : 'bg-danger-lt' }}">{{ $user->status ? __('Active') : __('Banned') }}</span>
                            <span class="badge {{ $approvalClass }}">{{ __($approvalLabel) }}</span>
                            <span class="badge {{ $user->kv == 1 ? 'bg-success-lt' : 'bg-secondary-lt' }}">@lang('KYC') {{ $kycLabel }}</span>
                            @if(!$user->agent_id && filled($user->zipcode))
                                <span class="badge bg-warning-lt">@lang('No agent')</span>
                            @endif
                        </td>
                        <td class="text-secondary">{{ optional($user->created_at)->format('M d, Y') }}</td>
                        <td><a class="btn btn-outline-primary" href="{{ route('toapp.admin.users.show', $user) }}">@lang('Open')</a></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-secondary py-4">@lang('No users found.')</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="card-footer">{{ $users->links() }}</div>
    @endif
</div>
@endsection
