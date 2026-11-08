@extends('toapp_admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-header portal-list-header">
        <h3 class="card-title">@lang('Insurance applications')</h3>
    </div>
    <form class="portal-filter-bar" method="GET">
        <div class="portal-filter-field">
            <label class="form-label">@lang('Search')</label>
            <input type="search" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="@lang('Reference, email, name')">
        </div>
        <div class="portal-filter-field">
            <label class="form-label">@lang('Status')</label>
            <select class="form-select form-select-sm" name="status">
                <option value="">@lang('All statuses')</option>
                @foreach([
                    'chatting' => 'Chatting',
                    'payment_pending' => 'Payment pending',
                    'confirmed' => 'Confirmed',
                    'rejected' => 'Rejected',
                ] as $st => $label)
                    <option value="{{ $st }}" @selected(request('status') === $st)>{{ __($label) }}</option>
                @endforeach
            </select>
        </div>
        <div class="portal-filter-actions">
            <button class="btn btn-primary" type="submit"><i class="ti ti-search me-1"></i> @lang('Search')</button>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead><tr><th>@lang('Ref')</th><th>@lang('Customer')</th><th>@lang('Product')</th><th>@lang('ZIP code')</th><th>@lang('Agent')</th><th>@lang('Status')</th><th class="w-1"></th></tr></thead>
            <tbody>
                @forelse($applications as $app)
                    <tr>
                        <td><code>{{ $app->reference }}</code></td>
                        <td>
                            <div class="fw-bold">{{ $app->user?->firstname }} {{ $app->user?->lastname }}</div>
                            <div class="text-secondary">{{ $app->user?->email }}</div>
                        </td>
                        <td>{{ $app->plan?->name ?: '—' }}</td>
                        <td>{{ $app->zipcode }}</td>
                        <td>{{ $app->agent?->name ?: '—' }}</td>
                        <td><span class="badge bg-secondary-lt">{{ __($app->statusLabel()) }}</span></td>
                        <td><a class="btn btn-sm" href="{{ route('toapp.admin.applications.show', $app) }}">@lang('Open')</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-secondary py-4">@lang('No applications yet.')</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($applications->hasPages())
        <div class="card-footer">{{ $applications->links() }}</div>
    @endif
</div>
@endsection
