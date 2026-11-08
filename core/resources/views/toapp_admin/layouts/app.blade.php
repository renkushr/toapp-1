<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $pageTitle ?? 'Admin' }} - TOAPP</title>
    <link rel="shortcut icon" type="image/png" href="{{ siteFavicon() }}">
    @include('partials.pwa_head', [
        'pwaName' => 'TOAPP Admin',
        'pwaManifest' => asset('manifest-admin.webmanifest'),
        'pwaTheme' => '#206bc4',
        'pwaBackground' => '#f1f5f9',
    ])
    @include('partials.tabler_assets')
    @stack('styles')
</head>
<body class="has-portal-footer">
    @php $currentAdmin = auth('admin')->user(); @endphp
    <div class="page">
        @include('toapp_admin.partials.sidebar')

        <div class="page-wrapper">
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="portal-page-header">
                        <div class="portal-page-header__title">
                            <div class="page-pretitle">@lang('Operations')</div>
                            <h2 class="page-title">{{ __($pageTitle ?? 'Dashboard') }}</h2>
                        </div>
                        <div class="portal-page-header__actions d-print-none">
                            <div class="portal-user-chip">
                                <span class="avatar avatar-sm">{{ strtoupper(substr($currentAdmin->username ?? 'A', 0, 1)) }}</span>
                                <span class="portal-user-chip__meta">
                                    <strong class="d-block" style="font-size: 0.85rem;">{{ $currentAdmin->username ?? 'admin' }}</strong>
                                    <small class="text-secondary">{{ $currentAdmin?->roleLabel() ?? __('Admin') }}</small>
                                </span>
                                <form action="{{ route('toapp.admin.logout') }}" method="POST" class="ms-1">
                                    @csrf
                                    <button class="btn btn-ghost-secondary btn-icon btn-sm" type="submit" title="@lang('Logout')">
                                        <i class="ti ti-logout"></i>
                                    </button>
                                </form>
                            </div>
                            @include('partials.language_switcher', ['variant' => 'portal'])
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-body">
                <div class="container-xl">
                    @if(session('status'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('status') }}
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="@lang('Close')"></a>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            {{ session('error') }}
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="@lang('Close')"></a>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            {{ $errors->first() }}
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="@lang('Close')"></a>
                        </div>
                    @endif

                    @yield('content')

                    @include('partials.site_copyright', ['copyrightVariant' => 'portal'])
                </div>
            </div>
        </div>
    </div>

    @include('partials.cookie_consent')
    @include('partials.language_switcher_assets')

    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js" defer></script>
    @stack('script-lib')
    @stack('scripts')
    @include('partials.pwa_scripts', [
        'pwaServiceWorker' => asset('service-worker-admin.js'),
        'pwaScope' => '/adto-app/',
        'pwaInstallUi' => false,
    ])
</body>
</html>
