@php
    $pwaName = $pwaName ?? (gs('site_name') ?: 'To-App');
    $pwaManifest = $pwaManifest ?? asset('manifest.webmanifest');
    $pwaTheme = $pwaTheme ?? '#5936f2';
    $pwaBackground = $pwaBackground ?? '#ffffff';
@endphp
<link rel="manifest" href="{{ $pwaManifest }}" id="toapp-manifest">
<link rel="apple-touch-icon" href="{{ asset('assets/global/img/pwa/apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/global/img/pwa/icon-192.png') }}">
<meta name="theme-color" content="{{ $pwaTheme }}" media="(prefers-color-scheme: light)">
<meta name="theme-color" content="{{ $pwaTheme }}" media="(prefers-color-scheme: dark)">
<meta name="msapplication-TileColor" content="{{ $pwaTheme }}">
<meta name="msapplication-navbutton-color" content="{{ $pwaTheme }}">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="{{ $pwaName }}">
<meta name="application-name" content="{{ $pwaName }}">
<meta name="mobile-web-app-capable" content="yes">
<meta name="format-detection" content="telephone=no">
