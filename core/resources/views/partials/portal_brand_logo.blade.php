@php
    $href = $href ?? url('/');
    $variant = $variant ?? 'default';
@endphp
<a href="{{ $href }}" class="portal-brand portal-brand--{{ $variant }}">
    <img
        src="{{ asset('assets/global/img/toapp-icon.png') }}"
        alt=""
        class="portal-brand-icon"
        width="40"
        height="40"
    >
    <img
        src="{{ asset('assets/global/img/toapp-wordmark.png') }}"
        alt="TOAPP"
        class="portal-brand-wordmark"
        width="72"
        height="32"
    >
</a>
