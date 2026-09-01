@extends($activeTemplate . 'layouts.app')
@section('panel')
   
    @guest
        @if (request()->routeIs('home'))
        @include($activeTemplate . 'partials.modal')
        @endif
    @endguest
    <div>
      
        @yield('content')
    </div>
   
@endsection
@push('script')
    <script>
        (function($) {
            "use strict";

            //Start-Id-Wise-Route-set
            let currentRoute = '{{ Route::currentRouteName() }}'
            let sectionArray = ['#about', '#plan', '#feature', '#faq', '#gateway'];
            if (currentRoute != 'home') {
                let links = $('#linkItem a');
                links.on('click', function() {
                    let section = $(this).attr('href');
                    let base = '{{ route('home') }}';
                    if (sectionArray.includes(section)) {
                        window.location = base + section;
                    }
                });
            }
            //End-Id-Wise-Route-set
        })(jQuery);
    </script>
@endpush
