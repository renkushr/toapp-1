<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">
    <title>{{ gs()->siteName(__($pageTitle ?? '')) }}</title>
    @include('partials.seo')
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<link href="{{ asset('assets/global/css/bootstrap/bootstrap.min.css')}}" rel="stylesheet">
<link href="{{ asset('assets/global/css/aos.css')}}" rel="stylesheet">
<link href="{{ asset('assets/global/css/jquery.fancybox.css')}}" rel="stylesheet">
<link href="{{ asset('assets/global/css/jquery.scrollbar.css')}}" rel="stylesheet">
<link href="{{ asset('assets/global/css/line-awesome.min.css')}}" rel="stylesheet">
<link href="{{ asset('assets/global/css/global.css')}}" rel="stylesheet">
@include('partials.pwa_head', [
    'pwaName' => gs('site_name') ?: 'To-App',
    'pwaTheme' => '#1268f3',
    'pwaBackground' => '#e8f2f8',
])
    @stack('style-lib')
    @stack('style')

</head>
@php echo loadExtension('google-analytics') @endphp

@php
    $showUserFooterNav = auth()->check()
        && (request()->routeIs('user.*') || request()->routeIs('ticket.*'))
        && !request()->routeIs(
            'user.authorization',
            'user.approval.pending',
            'user.send.verify.code',
            'user.verify.email',
            'user.verify.mobile',
            'user.2fa.verify'
        );
@endphp
<body @class(['has-user-footer-nav' => $showUserFooterNav])>

    <div class="preloader">
        <div class="preloader-container">
            <span class="animated-preloader"></span>
        </div>
    </div>

    <div class="body-overlay"></div>
    @yield('panel')

    @auth
        @if ($showUserFooterNav)
            @include($activeTemplate . 'partials.footer')
        @else
            @include('partials.site_copyright')
        @endif
    @else
        @unless (request()->routeIs('home'))
            @include('partials.site_copyright')
        @endunless
    @endauth

    @include('partials.cookie_consent')

   
    <script src="{{ asset('assets/global/js/jquery-3.4.1.min.js ') }}"></script>  
<script src="{{ asset('assets/global/js/bootstrap/popper.min.js ') }}"></script>
<script src="{{ asset('assets/global/js/bootstrap/bootstrap.min.js ') }}"></script>    
<script src="{{ asset('assets/global/js/jquery.fancybox.js ') }}"></script> 
<script src="{{ asset('assets/global/js/aos.js ') }}"></script>       
<script src="{{ asset('assets/global/js/jquery.scrollbar.js ') }}"></script> 
<script src="{{ asset('assets/global/js/custom.js ') }}"></script>    

    @stack('script-lib')
    @php echo loadExtension('tawk-chat') @endphp
    @include('partials.notify')

    @if (gs('pn'))
        @include('partials.push_script')
    @endif
    @auth
        @if ($showUserFooterNav)
            @include($activeTemplate . 'partials.user_chat_notifications')
        @endif
    @endauth
    @stack('script')
    <script>
        (function() {
            function hidePreloader() {
                document.querySelectorAll('.preload, .preloader').forEach(function(loader) {
                    loader.style.opacity = '0';
                    loader.style.visibility = 'hidden';
                    loader.style.pointerEvents = 'none';
                    loader.style.display = 'none';
                    loader.setAttribute('aria-hidden', 'true');
                });
                document.documentElement.classList.add('page-loaded');
            }

            window.addEventListener('load', hidePreloader);
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(hidePreloader, 400);
            });
            setTimeout(hidePreloader, 1500);
        })();
    </script>
    <script>
        (function($) {
            "use strict";
            $(".langSel").on("change", function() {
                window.location.href = "{{ route('home') }}/change/" + $(this).val();
            });

            var inputElements = $('[type=text],select,textarea');
            $.each(inputElements, function(index, element) {
                element = $(element);
                element.closest('.form-group').find('label').attr('for', element.attr('name'));
                element.attr('id', element.attr('name'))
            });

            $.each($('input, select, textarea'), function(i, element) {
                var elementType = $(element);
                if (elementType.attr('type') != 'checkbox') {
                    if (element.hasAttribute('required')) {
                        $(element).closest('.form-group').find('label').addClass('required');
                    }
                }
            });

            $('.select2').each(function(index, element) {
                $(element).select2({
                    minimumResultsForSearch: "-1"
                });
            });

            $('.select2-basic').each(function(index, element) {
                $(element).select2({
                    dropdownParent: $(element).closest('.select2-parent')
                });
            });

            let disableSubmission = false;
            $('.disableSubmission').on('submit', function(e) {
                if (disableSubmission) {
                    e.preventDefault()
                } else {
                    disableSubmission = true;
                }
            });

        })(jQuery);
    </script>
    @include('partials.pwa_scripts')
    @include('partials.user_pwa_install_assets')
    @include('partials.language_switcher_assets')
</body>

</html>
