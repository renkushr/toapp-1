<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{{ $pageTitle ?? 'Check Insure' }}</title>
    @include('partials.pwa_head', [
        'pwaName' => 'Check Insure',
        'pwaManifest' => asset('manifest-check-insure.webmanifest'),
        'pwaTheme' => '#1268f3',
    ])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="{{ asset('check-insure/css/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('check-insure/css/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('check-insure/css/jquery.fancybox.css') }}" rel="stylesheet">
    <link href="{{ asset('check-insure/css/jquery.scrollbar.css') }}" rel="stylesheet">
    <link href="{{ asset('check-insure/css/global.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
<div class="preload"><span class="loader"></span></div>
<div class="page @yield('page_class')">
    @include('check_insure.partials.header')

    @if(session('status'))
        <div class="container mt-3"><div class="alert alert-success mb-0">{{ session('status') }}</div></div>
    @endif
    @if($errors->any())
        <div class="container mt-3"><div class="alert alert-danger mb-0">{{ $errors->first() }}</div></div>
    @endif

    @yield('content')

    @hasSection('hide_footer')
    @else
        @include('check_insure.partials.footer')
    @endif
</div>

@include('partials.cookie_consent')

<script src="{{ asset('check-insure/js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('check-insure/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('check-insure/js/jquery.fancybox.js') }}"></script>
<script src="{{ asset('check-insure/js/aos.js') }}"></script>
<script src="{{ asset('check-insure/js/jquery.scrollbar.js') }}"></script>
<script src="{{ asset('check-insure/js/custom.js') }}"></script>
@stack('script-lib')
@stack('scripts')
@include('partials.pwa_scripts')
</body>
</html>
