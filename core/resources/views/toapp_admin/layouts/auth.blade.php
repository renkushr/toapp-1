<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $pageTitle ?? 'Sign in' }} - TOAPP</title>
    <link rel="shortcut icon" type="image/png" href="{{ siteFavicon() }}">
    @include('partials.pwa_head', [
        'pwaName' => 'TOAPP Admin',
        'pwaManifest' => asset('manifest-admin.webmanifest'),
        'pwaTheme' => '#206bc4',
        'pwaBackground' => '#f1f5f9',
    ])
    @include('partials.tabler_assets')
</head>
<body class="d-flex flex-column has-portal-footer">
    <div class="container-xl py-3 d-flex justify-content-end">
        @include('partials.language_switcher', ['variant' => 'portal'])
    </div>
    @yield('content')
    @include('partials.site_copyright', ['copyrightVariant' => 'portal'])
    @include('partials.cookie_consent')
    @include('partials.language_switcher_assets')
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js" defer></script>
    @include('partials.pwa_scripts', [
        'pwaServiceWorker' => asset('service-worker-admin.js'),
        'pwaScope' => '/adto-app/',
        'pwaInstallUi' => false,
    ])
</body>
</html>
