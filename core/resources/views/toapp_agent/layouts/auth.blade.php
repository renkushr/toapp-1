<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ isset($pageTitle) ? __($pageTitle) : __('Agent sign in') }} - TOAPP</title>
    @include('partials.pwa_head', [
        'pwaName' => 'TOAPP Agent',
        'pwaManifest' => asset('manifest-agent.webmanifest'),
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
        'pwaServiceWorker' => asset('service-worker-agent.js'),
        'pwaScope' => '/agent/',
        'pwaInstallUi' => false,
    ])
</body>
</html>
