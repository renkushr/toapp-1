@php
    $customCaptcha = loadCustomCaptcha();
    $googleCaptcha = loadReCaptcha();
@endphp
@if ($googleCaptcha)
    <div class="mb-3">
        @php echo $googleCaptcha @endphp
    </div>
@endif
@if ($customCaptcha)
    <div class="form-group auth-captcha-custom">
        <div class="mb-2">
            @php echo $customCaptcha @endphp
        </div>
        <label class="form-label auth-label">@lang('Captcha')</label>
        <input type="text" name="captcha" class="form-control form--control auth-control" required>
    </div>
@endif
@if ($googleCaptcha)
    @push('script')
        <script>
            (function($) {
                "use strict"
                $('.verify-gcaptcha').on('submit', function() {
                    var response = grecaptcha.getResponse();
                    if (response.length == 0) {
                        document.getElementById('g-recaptcha-error').innerHTML = '<span class="text--danger">@lang('Captcha field is required.')</span>';
                        return false;
                    }
                    return true;
                });

            })(jQuery);

            window.verifyCaptcha = () => {
                document.getElementById('g-recaptcha-error').innerHTML = '';
            }
        </script>
    @endpush
@endif
