@php
    use App\Constants\Status;
    use App\Models\Frontend;

    $siteCompany = 'TONKOW TEK LLP';
    $hasAcceptedCookie = (bool) request()->cookie('gdpr_cookie');
    $cookieConfig = Frontend::where('data_keys', 'cookie.data')->first();
    $cookieValues = $cookieConfig->data_values ?? null;
    $cookiePolicyEnabled = $cookieValues && (int) ($cookieValues->status ?? 0) === Status::ENABLE;
    $cookieMessage = trim(strip_tags((string) ($cookieValues->short_desc ?? '')));

    if ($cookieMessage === '') {
        $cookieMessage = 'We use cookies to improve your experience and keep our platform secure. By continuing, you agree to our cookie policy.';
    }
@endphp

@if (!$hasAcceptedCookie)
    <div class="site-cookie-consent" id="siteCookieConsent" role="dialog" aria-live="polite" aria-label="@lang('Cookie consent')">
        <div class="site-cookie-consent__panel">
            <div class="site-cookie-consent__icon" aria-hidden="true">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 2C8.5 2 6 4.5 6 8c0 .7.1 1.4.3 2C4.9 11.2 4 13 4 15c0 3.3 3.1 6 7 6h2c3.9 0 7-2.7 7-6 0-2-1-3.8-2.3-5 .2-.6.3-1.3.3-2 0-3.5-2.5-6-6-6Z" stroke="currentColor" stroke-width="1.6"/>
                    <circle cx="9" cy="10" r="1" fill="currentColor"/>
                    <circle cx="14" cy="9" r="1" fill="currentColor"/>
                    <circle cx="16" cy="13" r="1" fill="currentColor"/>
                </svg>
            </div>
            <div class="site-cookie-consent__body">
                <strong>@lang('Cookies')</strong>
                <p>{{ $cookieMessage }} {{ $siteCompany }} @lang('may store essential cookies on your device.')</p>
            </div>
            <div class="site-cookie-consent__actions">
                @if ($cookiePolicyEnabled)
                    <a class="site-cookie-consent__link" href="{{ route('cookie.policy') }}">@lang('Learn more')</a>
                @endif
                <button class="site-cookie-consent__accept" type="button" id="siteCookieAcceptBtn">
                    @lang('Accept')
                </button>
            </div>
        </div>
    </div>

    @once
        <style>
            .site-cookie-consent {
                position: fixed;
                left: 16px;
                right: 16px;
                bottom: 16px;
                z-index: 11000;
                pointer-events: none;
            }

            .site-cookie-consent__panel {
                pointer-events: auto;
                display: grid;
                grid-template-columns: auto 1fr auto;
                gap: 14px;
                align-items: center;
                max-width: 920px;
                margin: 0 auto;
                padding: 16px 18px;
                border: 1px solid rgba(255, 255, 255, .72);
                border-radius: 22px;
                background: rgba(255, 255, 255, .96);
                box-shadow: 0 22px 60px rgba(15, 23, 42, .18);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
            }

            .site-cookie-consent__icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 46px;
                height: 46px;
                border-radius: 16px;
                color: #1268f3;
                background: rgba(18, 104, 243, .1);
                font-size: 24px;
            }

            .site-cookie-consent__body strong {
                display: block;
                margin-bottom: 4px;
                color: #0b2033;
                font-size: 14px;
                font-weight: 600;
            }

            .site-cookie-consent__body p {
                margin: 0;
                color: #64748b;
                font-size: 13px;
                line-height: 1.5;
            }

            .site-cookie-consent__actions {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                justify-content: flex-end;
            }

            .site-cookie-consent__link,
            .site-cookie-consent__accept {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 40px;
                padding: 0 16px;
                border-radius: 999px;
                font-size: 13px;
                font-weight: 600;
                text-decoration: none;
                border: 0;
                cursor: pointer;
            }

            .site-cookie-consent__link {
                color: #1268f3;
                background: rgba(18, 104, 243, .08);
            }

            .site-cookie-consent__accept {
                color: #fff;
                background: linear-gradient(135deg, #1268f3, #13c8d6);
                box-shadow: 0 12px 28px rgba(18, 104, 243, .22);
            }

            body.has-user-footer-nav .site-cookie-consent {
                bottom: calc(88px + env(safe-area-inset-bottom, 0px));
            }

            body.has-portal-footer .site-cookie-consent {
                bottom: 72px;
            }

            @media (max-width: 767px) {
                .site-cookie-consent {
                    left: 10px;
                    right: 10px;
                    bottom: 10px;
                }

                .site-cookie-consent__panel {
                    grid-template-columns: 1fr;
                    gap: 12px;
                }

                .site-cookie-consent__actions {
                    justify-content: stretch;
                }

                .site-cookie-consent__link,
                .site-cookie-consent__accept {
                    flex: 1 1 auto;
                }

                body.has-user-footer-nav .site-cookie-consent {
                    bottom: calc(80px + env(safe-area-inset-bottom, 0px));
                }
            }
        </style>
        <script>
            (function() {
                var acceptBtn = document.getElementById('siteCookieAcceptBtn');
                if (!acceptBtn) {
                    return;
                }

                acceptBtn.addEventListener('click', function() {
                    var banner = document.getElementById('siteCookieConsent');
                    fetch("{{ route('cookie.accept') }}", {
                        credentials: 'same-origin',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    }).finally(function() {
                        if (banner) {
                            banner.remove();
                        }
                    });
                });
            })();
        </script>
    @endonce
@endif
