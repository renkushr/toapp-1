<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle ?? 'Admin' }} - TOAPP</title>
    <link rel="shortcut icon" type="image/png" href="{{ siteFavicon() }}">
    @include('partials.pwa_head', ['pwaName' => 'TOAPP Admin'])
    @include('partials.tabler_assets')
</head>
<body class="has-portal-footer">
    @php $currentAdmin = auth('admin')->user(); @endphp
    <div class="page">
        @include('toapp_admin.partials.sidebar')

        <div class="page-wrapper">
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <div class="page-pretitle">Operations</div>
                            <h2 class="page-title">{{ $pageTitle ?? 'Dashboard' }}</h2>
                        </div>
                        <div class="col-auto ms-auto d-print-none">
                            <div class="portal-user-chip">
                                <span class="avatar avatar-sm">{{ strtoupper(substr($currentAdmin->username ?? 'A', 0, 1)) }}</span>
                                <span>
                                    <strong class="d-block" style="font-size: 0.85rem;">{{ $currentAdmin->username ?? 'admin' }}</strong>
                                    <small class="text-secondary">{{ $currentAdmin?->roleLabel() ?? 'Admin' }}</small>
                                </span>
                                <form action="{{ route('toapp.admin.logout') }}" method="POST" class="ms-1">
                                    @csrf
                                    <button class="btn btn-ghost-secondary btn-icon btn-sm" type="submit" title="Logout">
                                        <i class="ti ti-logout"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-body">
                <div class="container-xl">
                    @if(session('status'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('status') }}
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            {{ session('error') }}
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            {{ $errors->first() }}
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
                    @endif

                    @yield('content')

                    @include('partials.site_copyright', ['copyrightVariant' => 'portal'])
                </div>
            </div>
        </div>
    </div>

    @include('partials.cookie_consent')

    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js" defer></script>
    @stack('script-lib')
    @stack('scripts')
    @include('partials.pwa_scripts')
</body>
</html>
