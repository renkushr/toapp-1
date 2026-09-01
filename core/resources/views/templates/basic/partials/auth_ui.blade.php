@once
    @push('style')
        <style>
            .auth-surface {
                min-height: 100vh;
                padding: 24px 16px 40px;
                overflow-x: hidden;
                background:
                    linear-gradient(180deg, rgba(255, 255, 255, 0.86) 0%, rgba(242, 250, 253, 0.92) 54%, rgba(228, 245, 241, 0.92) 100%),
                    url("{{ asset('assets/global/img/bg-app.png') }}") center top / cover no-repeat;
                color: #182033;
            }

            .auth-shell {
                width: min(100%, 430px);
                margin: 0 auto;
            }

            .auth-shell *,
            .auth-app-modal * {
                min-width: 0;
            }

            .auth-topbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                min-height: 44px;
                margin-bottom: 18px;
            }

            .auth-topbar--end {
                justify-content: flex-end;
            }

            .auth-back {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 42px;
                height: 42px;
                border: 1px solid rgba(24, 171, 207, 0.24);
                border-radius: 8px;
                background: rgba(255, 255, 255, 0.84);
                color: #015086;
                box-shadow: 0 12px 30px rgba(1, 80, 134, 0.08);
            }

            .auth-brand {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                margin-bottom: 18px;
            }

            .auth-brand img {
                max-height: 54px;
                width: auto;
            }

            .auth-card {
                border: 1px solid rgba(1, 80, 134, 0.08);
                border-radius: 8px;
                background: rgba(255, 255, 255, 0.94);
                box-shadow: 0 24px 60px rgba(1, 80, 134, 0.12);
                backdrop-filter: blur(14px);
                overflow: hidden;
            }

            .auth-card__header {
                padding: 24px 24px 18px;
                border-bottom: 1px solid rgba(1, 80, 134, 0.08);
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.96), rgba(237, 250, 249, 0.96));
            }

            .auth-eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 12px;
                color: #0c7e8b;
                font-size: 12px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0;
            }

            .auth-title {
                margin: 0;
                color: #172033;
                font-size: 28px;
                font-weight: 600;
                line-height: 1.18;
                letter-spacing: 0;
            }

            .auth-copy {
                margin: 10px 0 0;
                color: #647083;
                font-size: 14px;
                line-height: 1.55;
            }

            .auth-card__body {
                padding: 22px 24px 24px;
            }

            .auth-progress {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 8px;
                margin-bottom: 22px;
            }

            .auth-step {
                min-height: 6px;
                border-radius: 999px;
                background: #d8e8ee;
            }

            .auth-step.is-active,
            .auth-step.is-complete {
                background: linear-gradient(90deg, #18abcf, #25c99b);
            }

            .auth-form {
                display: grid;
                gap: 16px;
            }

            .auth-field {
                display: grid;
                gap: 8px;
            }

            .auth-label-row {
                display: flex;
                justify-content: space-between;
                align-items: baseline;
                gap: 12px;
            }

            .auth-label {
                color: #1f2a3f;
                font-size: 14px;
                font-weight: 600;
            }

            .auth-optional {
                color: #8894a6;
                font-size: 12px;
                font-weight: 600;
            }

            .auth-control,
            .auth-select {
                width: 100%;
                min-height: 54px;
                padding: 0 18px;
                border: 1px solid #d9e8ef;
                border-radius: 8px;
                background: #fff;
                color: #172033;
                font: inherit;
                font-size: 15px;
                box-shadow: none;
                transition: border-color 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease;
            }

            .auth-control::placeholder {
                color: #9aa6b5;
            }

            .auth-control:focus,
            .auth-select:focus {
                border-color: #18abcf;
                outline: 0;
                box-shadow: 0 0 0 4px rgba(24, 171, 207, 0.12);
            }

            .auth-help {
                margin: 0;
                color: #718096;
                font-size: 12px;
                line-height: 1.45;
            }

            .auth-link {
                color: #0a82aa;
                font-weight: 600;
            }

            .auth-error-summary {
                display: grid;
                gap: 6px;
                padding: 12px 14px;
                border: 1px solid rgba(232, 89, 74, 0.28);
                border-radius: 8px;
                background: #fff3f0;
                color: #a33a2e;
                font-size: 13px;
                line-height: 1.45;
            }

            .auth-choice-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            .auth-choice {
                min-height: 54px;
                border: 1px solid #d9e8ef;
                border-radius: 8px;
                background: #fff;
                color: #273348;
                font-weight: 600;
                transition: border-color 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease;
            }

            .auth-choice.active,
            .auth-choice:focus,
            .auth-choice:hover {
                border-color: #18abcf;
                background: #eefbfc;
                color: #015086;
                box-shadow: 0 10px 28px rgba(24, 171, 207, 0.12);
            }

            .auth-date-grid {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 10px;
            }

            .auth-password-wrap {
                position: relative;
            }

            .auth-password-wrap .auth-control {
                padding-right: 48px;
            }

            .auth-password-toggle {
                position: absolute;
                top: 50%;
                right: 12px;
                transform: translateY(-50%);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 34px;
                height: 34px;
                border: 0;
                border-radius: 8px;
                background: #eef7fa;
                color: #015086;
            }

            .password-meter {
                display: grid;
                gap: 7px;
                margin-top: 8px;
            }

            .password-meter__track {
                width: 100%;
                height: 6px;
                border-radius: 999px;
                background: #e5eef2;
                overflow: hidden;
            }

            .password-meter__track span {
                display: block;
                width: 0;
                height: 100%;
                border-radius: inherit;
                transition: width 0.2s ease, background-color 0.2s ease;
            }

            .password-meter__label {
                color: #718096;
                font-size: 12px;
                line-height: 1.35;
            }

            .auth-submit {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                min-height: 56px;
                margin-top: 4px;
                border: 0;
                border-radius: 8px;
                background: linear-gradient(135deg, #0ea5c0 0%, #1f83f0 60%, #25c99b 100%);
                color: #fff;
                font-size: 16px;
                font-weight: 600;
                box-shadow: 0 18px 34px rgba(24, 132, 191, 0.28);
                transition: transform 0.18s ease, box-shadow 0.18s ease, opacity 0.18s ease;
            }

            .auth-submit:hover {
                transform: translateY(-1px);
                box-shadow: 0 22px 42px rgba(24, 132, 191, 0.32);
            }

            .auth-submit.is-loading {
                opacity: 0.75;
                pointer-events: none;
            }

            .auth-captcha .form-group {
                margin-bottom: 0;
            }

            .auth-captcha .form-label,
            .auth-captcha .auth-label {
                color: #1f2a3f;
                font-size: 14px;
                font-weight: 600;
                margin-bottom: 8px;
            }

            .auth-captcha .form-control,
            .auth-captcha .auth-control {
                width: 100%;
                min-height: 54px;
                padding: 0 18px;
                border: 1px solid #d9e8ef;
                border-radius: 8px;
                background: #fff;
            }

            .auth-note {
                display: flex;
                gap: 10px;
                align-items: flex-start;
                padding: 12px;
                border-radius: 8px;
                background: #f5fbf8;
                color: #496173;
                font-size: 13px;
                line-height: 1.45;
            }

            .auth-note__icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                flex: 0 0 26px;
                width: 26px;
                height: 26px;
                border-radius: 8px;
                background: #dff7ed;
                color: #087a55;
                font-weight: 600;
            }

            .auth-footer-text {
                margin: 18px 0 0;
                color: #637185;
                font-size: 14px;
                text-align: center;
            }

            .auth-app-modal .modal-content {
                border: 0;
                border-radius: 8px;
                box-shadow: 0 28px 70px rgba(23, 32, 51, 0.24);
                overflow: hidden;
            }

            .auth-app-modal .modal-header {
                border: 0;
                padding: 22px 22px 8px;
            }

            .auth-app-modal .modal-title {
                color: #172033;
                font-size: 20px;
                font-weight: 600;
            }

            .auth-app-modal .modal-body {
                padding: 0 22px 22px;
                color: #647083;
                font-size: 14px;
                line-height: 1.55;
            }

            .auth-app-modal .modal-footer {
                gap: 10px;
                border: 0;
                padding: 0 22px 22px;
            }

            .auth-modal-button {
                min-height: 44px;
                border-radius: 8px;
                font-weight: 600;
            }

            @media (max-width: 575px) {
                .auth-surface {
                    min-height: 100svh;
                    padding: 14px 10px 28px;
                }

                .auth-topbar {
                    position: sticky;
                    top: 8px;
                    z-index: 10;
                    min-height: 40px;
                    margin-bottom: 12px;
                }

                .auth-back {
                    width: 40px;
                    height: 40px;
                }

                .auth-card__header,
                .auth-card__body {
                    padding-left: 16px;
                    padding-right: 16px;
                }

                .auth-title {
                    font-size: 24px;
                }

                .auth-copy {
                    font-size: 13px;
                }

                .auth-choice-grid,
                .auth-date-grid {
                    grid-template-columns: 1fr;
                }

                .auth-control,
                .auth-select,
                .auth-submit,
                .auth-choice {
                    min-height: 50px;
                    font-size: 14px;
                }

                .auth-app-modal .modal-dialog {
                    margin: 12px;
                }
            }

            @media (max-width: 380px) {
                .auth-brand img {
                    max-height: 46px;
                }

                .auth-card__header {
                    padding-top: 18px;
                }

                .auth-card__body {
                    padding-bottom: 18px;
                }
            }
        </style>
    @endpush

    @push('script')
        <script>
            (function($) {
                "use strict";

                $('.auth-enhanced-form').on('submit', function() {
                    const form = this;
                    const button = $(form).find('.auth-submit');

                    if (!form.checkValidity()) {
                        notify('error', 'Please complete the highlighted fields before continuing.');
                        return;
                    }

                    button.addClass('is-loading').text(button.data('loading-text') || 'Please wait...');
                });

                $('.auth-password-toggle').on('click', function() {
                    const target = $($(this).data('target'));
                    const isPassword = target.attr('type') === 'password';
                    target.attr('type', isPassword ? 'text' : 'password');
                    $(this).find('i').toggleClass('la-eye la-eye-slash');
                });
            })(jQuery);
        </script>
    @endpush
@endonce
