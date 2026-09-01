@php
    $pwaName = $pwaName ?? (gs('site_name') ?: 'To-app');
    $pwaManifest = $pwaManifest ?? asset('manifest.webmanifest');
    $pwaTheme = $pwaTheme ?? '#5936f2';
@endphp
<link rel="manifest" href="{{ $pwaManifest }}" id="toapp-manifest">
<link rel="apple-touch-icon" href="{{ asset('assets/global/img/pwa/apple-touch-icon.png') }}">
<meta name="theme-color" content="{{ $pwaTheme }}">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="{{ $pwaName }}">
<meta name="mobile-web-app-capable" content="yes">
