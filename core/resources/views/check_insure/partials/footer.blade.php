@php
    $supportEmail = trim((string) (gs('support_email') ?: config('toapp.support_email', 'tonkowtek@to-app.com')));
@endphp
<footer class="footer" data-aos="fade-in" data-aos-offet="0">
    <div class="container">
        <hr>
        <div class="row">
            <div class="col-md-8">
                <div class="logo">
                    <img class="w-100" src="{{ asset('check-insure/img/logo.svg') }}" alt="Check Insure">
                </div>
                <p>&copy; {{ date('Y') }} TONKOW TEK LLP. @lang('All rights reserved.')</p>
            </div>
            <div class="col-md-4 d-flex justify-content-end flex-column">
                @if($supportEmail !== '')
                    <p class="contact-item mb-0">
                        <a href="mailto:{{ $supportEmail }}">
                            <img class="icons" src="{{ asset('check-insure/img/icons/icon-email-1.svg') }}" alt="">
                            {{ $supportEmail }}
                        </a>
                    </p>
                @endif
            </div>
        </div>
    </div>
</footer>
