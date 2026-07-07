<link href="{{ asset('assets/global/css/iziToast.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/global/css/iziToast_custom.css') }}" rel="stylesheet">
<script src="{{ asset('assets/global/js/iziToast.min.js') }}"></script>
<style>
    .iziToast {
        border: 1px solid rgba(23, 32, 51, 0.08) !important;
        border-radius: 8px !important;
        box-shadow: 0 18px 50px rgba(23, 32, 51, 0.16) !important;
        font-family: "Poppins", sans-serif !important;
        overflow: hidden !important;
    }

    .iziToast::after {
        box-shadow: none !important;
    }

    .iziToast-title {
        color: #172033 !important;
        font-weight: 600 !important;
    }

    .iziToast-message {
        color: #647083 !important;
        line-height: 1.45 !important;
    }

    .iziToast-close {
        opacity: 0.7 !important;
    }
</style>

<script>
    "use strict";
    const colors = {
        success: '#18a46f',
        error: '#d94f3d',
        warning: '#d98b22',
        info: '#0e90b5',
    }

    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-times-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-exclamation-circle',
    }

    const notifications = @json(session('notify', []));
    const errors = @json(@$errors ? collect($errors->all())->unique() : []);


    const triggerToaster = (status, message) => {
        iziToast[status]({
            title: status.charAt(0).toUpperCase() + status.slice(1),
            message: message,
            position: window.innerWidth < 768 ? "topCenter" : "topRight",
            backgroundColor: '#fff',
            icon: icons[status],
            iconColor: colors[status],
            progressBarColor: colors[status],
            timeout: 5200,
            close: true,
            titleSize: '0.95rem',
            messageSize: '0.9rem',
            titleColor: '#474747',
            messageColor: '#a2a2a2',
            transitionIn: 'fadeInDown',
            transitionOut: 'fadeOutUp'
        });
    }

    if (notifications.length) {
        notifications.forEach(element => {
            triggerToaster(element[0], element[1]);
        });
    }

    if (errors.length) {
        errors.forEach(error => {
            triggerToaster('error', error);
        });
    }

    function notify(status, message) {
        if (typeof message == 'string') {
            triggerToaster(status, message);
        } else {
            $.each(message, (i, val) => triggerToaster(status, val));
        }
    }
</script>
