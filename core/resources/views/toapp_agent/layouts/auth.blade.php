<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle ?? 'Agent sign in' }} - TOAPP</title>
    @include('partials.pwa_head', ['pwaName' => 'TOAPP Agent'])
    @include('partials.tabler_assets')
</head>
<body class="d-flex flex-column has-portal-footer">
    @yield('content')
    @include('partials.site_copyright', ['copyrightVariant' => 'portal'])
    @include('partials.cookie_consent')
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js" defer></script>
    @include('partials.pwa_scripts')
</body>
</html>
