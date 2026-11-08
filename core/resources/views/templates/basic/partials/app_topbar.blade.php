@php
    $backUrl = $backUrl ?? route('user.home');
    $backLabel = $backLabel ?? __('Back');
    $brandUrl = $brandUrl ?? route('user.home');
    $brandTitle = $brandTitle ?? 'To-App';
    $brandLogo = $brandLogo ?? true;
@endphp
<div class="app-topbar">
    <a class="app-icon-btn" href="{{ $backUrl }}" aria-label="{{ $backLabel }}">
        <i class="las la-arrow-left"></i>
    </a>
    <a class="app-brand" href="{{ $brandUrl }}">
        @if($brandLogo)
            <img src="{{ siteLogo() }}" alt="To-App">
        @endif
        <span>{{ $brandTitle }}</span>
    </a>
    @isset($rightSlot)
        {!! $rightSlot !!}
    @else
        <span class="app-icon-btn" style="visibility:hidden" aria-hidden="true"><i class="las la-ellipsis-h"></i></span>
    @endisset
</div>
