@extends($activeTemplate . 'layouts.master')

@section('content')
@php
    $IA = \App\Models\InsuranceApplication::class;
    $statusFilters = [
        '' => __('All'),
        $IA::STATUS_TERMS_ACCEPTED => __('Waiting for agent'),
        $IA::STATUS_CHATTING => __('Chatting with agent'),
        $IA::STATUS_PAYMENT_PENDING => __('Awaiting payment confirmation'),
        $IA::STATUS_CONFIRMED => __('Confirmed'),
        $IA::STATUS_REJECTED => __('Rejected'),
    ];
    $badgeClass = fn ($status) => match ($status) {
        $IA::STATUS_CONFIRMED => 'badge--success',
        $IA::STATUS_REJECTED => 'badge--danger',
        $IA::STATUS_PAYMENT_PENDING, $IA::STATUS_TERMS_ACCEPTED => 'badge--warning',
        default => 'badge--primary',
    };
@endphp
<div class="app-page">
    <div class="app-container">
        <div class="app-topbar">
            <a class="app-icon-btn" href="{{ route('user.home') }}" aria-label="@lang('Back')">
                <i class="las la-arrow-left"></i>
            </a>
            <a class="app-brand" href="{{ route('user.home') }}">
                <img src="{{ siteLogo() }}" alt="To-App">
                <span>@lang('My Packages')</span>
            </a>
            <a class="app-icon-btn" href="{{ route('user.plans.survey') }}" aria-label="@lang('Add package')">
                <i class="las la-plus"></i>
            </a>
        </div>

        <section class="app-hero">
            <span class="app-eyebrow"><i class="las la-shield-alt"></i> @lang('My Packages')</span>
            <h1>@lang('Your insurance packages')</h1>
            <p>@lang('See every package you have requested, its current status, and continue where you left off.')</p>
            <div class="app-hero-actions">
                <a class="app-btn app-btn-primary" href="{{ route('user.plans.survey') }}">
                    <i class="las la-plus"></i>
                    @lang('Add package')
                </a>
            </div>
        </section>

        <div class="app-pills my-3">
            @foreach($statusFilters as $value => $label)
                <a class="app-pill {{ (string) request('status', '') === (string) $value ? 'active' : '' }}"
                   href="{{ route('user.plans.mine', $value !== '' ? ['status' => $value] : []) }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="app-section-title">
            <div>
                <h2>@lang('Package history')</h2>
                <p>@lang('Newest packages are shown first.')</p>
            </div>
        </div>

        <div class="app-table-shell table-responsive--md">
            <table class="table custom--table">
                <thead>
                    <tr>
                        <th>@lang('Reference')</th>
                        <th>@lang('Product')</th>
                        <th>@lang('Coverage amount')</th>
                        <th>@lang('Quote')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Date')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                        @php
                            $isMonthly = $application->pay_type === 'month';
                            $quote = $isMonthly ? $application->quoted_monthly : $application->quoted_annual;
                            $canChat = $user->agent_id && in_array($application->status, [$IA::STATUS_CHATTING, $IA::STATUS_PAYMENT_PENDING, $IA::STATUS_CONFIRMED], true);
                        @endphp
                        <tr>
                            <td>{{ $application->reference }}</td>
                            <td>{{ $application->plan?->name ?? __('Being prepared by your agent') }}</td>
                            <td>{{ $application->coverage_amount ? '$' . number_format((int) $application->coverage_amount) : '—' }}</td>
                            <td>
                                @if((float) $quote > 0)
                                    ${{ number_format((float) $quote, 2) }} / {{ $isMonthly ? __('mo') : __('yr') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td><span class="badge {{ $badgeClass($application->status) }}">{{ __($application->statusLabel()) }}</span></td>
                            <td>{{ showDateTime($application->created_at, 'M d, Y') }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2 justify-content-end justify-content-md-start">
                                    @if($canChat)
                                        <a class="app-link" href="{{ route('user.plans.chat', $application) }}">@lang('Chat')</a>
                                    @elseif($application->status === $IA::STATUS_TERMS_ACCEPTED)
                                        <a class="app-link" href="{{ route('user.plans.waiting', $application) }}">@lang('View')</a>
                                    @endif
                                    @if($application->plan_id && $application->canUserEditConditions())
                                        <a class="app-link" href="{{ route('user.plans.conditions.edit', $application) }}">@lang('Edit')</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center text-muted py-4">
                                @lang('You have no packages yet.')
                                <a class="app-link" href="{{ route('user.plans.survey') }}">@lang('Add package')</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($applications->hasPages())
            <div class="pt-3">
                {{ paginateLinks($applications) }}
            </div>
        @endif
    </div>
</div>
@endsection
