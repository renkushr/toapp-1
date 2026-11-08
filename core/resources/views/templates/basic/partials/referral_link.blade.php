<div class="form-group">
    <label>@lang('Referral Link')</label>
    <div class="input-group">
        <input type="text" class="form--control border" id="referralURL" value="{{ route('home') }}?reference={{ auth()->user()->username }}" readonly>
        <div class="input-group-text bg--base">
            <span class="copytext copyBoard" id="copyBoard"> <i class="la la-copy"></i> </span>
        </div>
    </div>
</div>


@push('style')
    <style type="text/css">
        #copyBoard {
            cursor: pointer;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.copyBoard').on("click", function() {
                "use strict";
                var copyText = document.getElementById("referralURL");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                /*For mobile devices*/
                document.execCommand("copy");
                iziToast.success({
                    message: @json(__('Copied:')) + " " + copyText.value,
                    position: "topRight"
                });
            });
        })(jQuery);
    </script>
@endpush
