<style>
    .mobile-shortcut {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .mobile-shortcut__button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        width: 100%;
        min-height: 46px;
        border: 0;
        border-radius: 999px;
        background: linear-gradient(135deg, #1268f3, #13c8d6);
        color: #fff;
        font-weight: 700;
        box-shadow: 0 12px 28px rgba(18, 104, 243, 0.22);
        cursor: pointer;
    }

    .app-hero-actions .mobile-shortcut {
        margin-top: 0;
    }

    .app-hero-actions .mobile-shortcut__button {
        width: auto;
        min-height: 48px;
        padding: 0 1.2rem;
    }

    .pwa-install-modal {
        position: fixed;
        inset: 0;
        z-index: 12000;
        display: none;
        align-items: flex-end;
        justify-content: center;
        padding: 1rem;
        background: rgba(15, 23, 42, 0.55);
    }

    .pwa-install-modal.is-open {
        display: flex;
    }

    .pwa-install-modal__sheet {
        width: min(100%, 420px);
        border-radius: 24px 24px 18px 18px;
        background: #fff;
        padding: 1.25rem 1.25rem 1.5rem;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.28);
        animation: pwaSheetUp .24s ease;
    }

    @keyframes pwaSheetUp {
        from { transform: translateY(16px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .pwa-install-modal__head {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        margin-bottom: 1rem;
    }

    .pwa-install-modal__icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        box-shadow: 0 8px 20px rgba(18, 104, 243, 0.18);
    }

    .pwa-install-modal__title {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 700;
        color: #172033;
    }

    .pwa-install-modal__text {
        margin: 0.2rem 0 0;
        color: #64748b;
        font-size: 0.9rem;
        line-height: 1.45;
    }

    .pwa-install-steps {
        margin: 0 0 1rem;
        padding: 0;
        list-style: none;
    }

    .pwa-install-steps li {
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
        padding: 0.7rem 0;
        border-bottom: 1px solid #eef2f7;
        color: #334155;
        font-size: 0.92rem;
        line-height: 1.45;
    }

    .pwa-install-steps li:last-child {
        border-bottom: 0;
    }

    .pwa-install-steps strong {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 1.5rem;
        height: 1.5rem;
        border-radius: 999px;
        background: rgba(18, 104, 243, 0.12);
        color: #1268f3;
        font-size: 0.78rem;
    }

    .pwa-install-modal__actions {
        display: flex;
        gap: 0.65rem;
    }

    .pwa-install-modal__primary,
    .pwa-install-modal__secondary {
        flex: 1;
        min-height: 46px;
        border-radius: 999px;
        border: 0;
        font-weight: 700;
        cursor: pointer;
    }

    .pwa-install-modal__primary {
        background: linear-gradient(135deg, #1268f3, #13c8d6);
        color: #fff;
    }

    .pwa-install-modal__secondary {
        background: #f1f5f9;
        color: #334155;
    }

    @media (min-width: 768px) {
        .mobile-shortcut {
            display: none;
        }

        .pwa-install-modal,
        .pwa-install-banner {
            display: none !important;
        }
    }

    @media (display-mode: standalone), (display-mode: minimal-ui) {
        .mobile-shortcut,
        .pwa-install-modal,
        .pwa-install-banner {
            display: none !important;
        }
    }

    html.toapp-standalone .mobile-shortcut,
    html.toapp-standalone .pwa-install-modal,
    html.toapp-standalone .pwa-install-banner,
    body.toapp-standalone .mobile-shortcut,
    body.toapp-standalone .pwa-install-modal,
    body.toapp-standalone .pwa-install-banner {
        display: none !important;
    }

    .pwa-install-banner {
        position: fixed;
        left: 12px;
        right: 12px;
        bottom: calc(88px + env(safe-area-inset-bottom, 0px));
        z-index: 11000;
        display: none;
        align-items: center;
        gap: 0.75rem;
        padding: 0.85rem 0.95rem;
        border-radius: 18px;
        background: rgba(11, 32, 51, .94);
        color: #fff;
        box-shadow: 0 18px 40px rgba(15, 23, 42, .28);
    }

    .pwa-install-banner.is-open {
        display: flex;
    }

    .pwa-install-banner img {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        flex: 0 0 auto;
    }

    .pwa-install-banner__copy {
        flex: 1 1 auto;
        min-width: 0;
    }

    .pwa-install-banner__copy strong {
        display: block;
        font-size: 0.92rem;
        line-height: 1.25;
    }

    .pwa-install-banner__copy span {
        display: block;
        margin-top: 2px;
        font-size: 0.78rem;
        opacity: .82;
        line-height: 1.35;
    }

    .pwa-install-banner__actions {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        flex: 0 0 auto;
    }

    .pwa-install-banner__install,
    .pwa-install-banner__close {
        border: 0;
        cursor: pointer;
        font-weight: 700;
    }

    .pwa-install-banner__install {
        min-height: 36px;
        padding: 0 0.85rem;
        border-radius: 999px;
        background: linear-gradient(135deg, #1268f3, #13c8d6);
        color: #fff;
        font-size: 0.82rem;
    }

    .pwa-install-banner__close {
        width: 32px;
        height: 32px;
        border-radius: 999px;
        background: rgba(255, 255, 255, .12);
        color: #fff;
    }
</style>
<script>
    (function() {
        "use strict";

        const iconUrl = @json(asset('assets/global/img/pwa/apple-touch-icon.png'));
        const dismissKey = 'toapp_pwa_banner_dismissed_at';
        const installedKey = 'toapp_pwa_installed';
        const isIos = /iphone|ipad|ipod/i.test(window.navigator.userAgent);
        const isAndroid = /android/i.test(window.navigator.userAgent);
        const isStandalone = window.matchMedia('(display-mode: standalone)').matches
            || window.navigator.standalone === true
            || document.documentElement.classList.contains('toapp-standalone');

        let modal = null;
        let banner = null;

        function toast(type, message) {
            if (typeof notify === 'function') {
                notify(type, message);
            }
        }

        function wasDismissedRecently() {
            try {
                const raw = localStorage.getItem(dismissKey);
                if (!raw) return false;
                return (Date.now() - Number(raw)) < (7 * 24 * 60 * 60 * 1000);
            } catch (e) {
                return false;
            }
        }

        function markDismissed() {
            try {
                localStorage.setItem(dismissKey, String(Date.now()));
            } catch (e) {}
        }

        function ensureModal() {
            if (modal) return modal;

            modal = document.createElement('div');
            modal.className = 'pwa-install-modal';
            modal.innerHTML = `
                <div class="pwa-install-modal__sheet" role="dialog" aria-modal="true" aria-labelledby="pwa-install-title">
                    <div class="pwa-install-modal__head">
                        <img class="pwa-install-modal__icon" src="${iconUrl}" alt="To-App">
                        <div>
                            <h3 class="pwa-install-modal__title" id="pwa-install-title">@lang('Install To-App')</h3>
                            <p class="pwa-install-modal__text" data-pwa-install-subtitle>@lang('Open like a real app from your phone.')</p>
                        </div>
                    </div>
                    <ol class="pwa-install-steps" data-pwa-install-steps></ol>
                    <div class="pwa-install-modal__actions">
                        <button type="button" class="pwa-install-modal__secondary" data-pwa-install-close>@lang('Not now')</button>
                        <button type="button" class="pwa-install-modal__primary" data-pwa-install-action>@lang('Install')</button>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeModal();
                }
            });

            modal.querySelector('[data-pwa-install-close]').addEventListener('click', closeModal);

            return modal;
        }

        function ensureBanner() {
            if (banner) return banner;

            banner = document.createElement('div');
            banner.className = 'pwa-install-banner';
            banner.innerHTML = `
                <img src="${iconUrl}" alt="To-App">
                <div class="pwa-install-banner__copy">
                    <strong>@lang('Install To-App')</strong>
                    <span>@lang('Add the app icon to your phone for faster access.')</span>
                </div>
                <div class="pwa-install-banner__actions">
                    <button type="button" class="pwa-install-banner__install" data-pwa-banner-install>@lang('Install')</button>
                    <button type="button" class="pwa-install-banner__close" data-pwa-banner-close aria-label="@lang('Close')">×</button>
                </div>
            `;
            document.body.appendChild(banner);
            banner.querySelector('[data-pwa-banner-install]').addEventListener('click', handleInstallClick);
            banner.querySelector('[data-pwa-banner-close]').addEventListener('click', function() {
                markDismissed();
                closeBanner();
            });
            return banner;
        }

        function closeModal() {
            if (modal) {
                modal.classList.remove('is-open');
            }
        }

        function closeBanner() {
            if (banner) {
                banner.classList.remove('is-open');
            }
        }

        function maybeShowBanner() {
            if (isStandalone || wasDismissedRecently()) {
                return;
            }
            try {
                if (localStorage.getItem(installedKey) === '1') {
                    return;
                }
            } catch (e) {}

            if (!(isIos || isAndroid || window.__toappPwaPrompt)) {
                return;
            }

            ensureBanner().classList.add('is-open');
        }

        function openModal(mode) {
            const el = ensureModal();
            const steps = el.querySelector('[data-pwa-install-steps]');
            const action = el.querySelector('[data-pwa-install-action]');
            const subtitle = el.querySelector('[data-pwa-install-subtitle]');

            if (mode === 'ios') {
                subtitle.textContent = @json(__('Use Safari Share menu to add the app icon.'));
                steps.innerHTML = `
                    <li><strong>1</strong><span>@lang('Tap the Share button at the bottom of Safari.')</span></li>
                    <li><strong>2</strong><span>@lang('Scroll and tap Add to Home Screen.')</span></li>
                    <li><strong>3</strong><span>@lang('Tap Add in the top-right corner.')</span></li>
                `;
                action.textContent = @json(__('Got it'));
                action.onclick = closeModal;
            } else if (mode === 'android-manual') {
                subtitle.textContent = @json(__('Use your browser menu to install the app.'));
                steps.innerHTML = `
                    <li><strong>1</strong><span>@lang('Tap the browser menu (top-right).')</span></li>
                    <li><strong>2</strong><span>@lang('Choose Install app or Add to Home screen.')</span></li>
                    <li><strong>3</strong><span>@lang('Confirm to add the To-App icon.')</span></li>
                `;
                action.textContent = @json(__('Got it'));
                action.onclick = closeModal;
            } else {
                subtitle.textContent = @json(__('Install To-App directly from this prompt.'));
                steps.innerHTML = `
                    <li><strong>1</strong><span>@lang('Tap Install below.')</span></li>
                    <li><strong>2</strong><span>@lang('Confirm in the browser install dialog.')</span></li>
                    <li><strong>3</strong><span>@lang('Open To-App from your home screen.')</span></li>
                `;
                action.textContent = @json(__('Install now'));
                action.onclick = triggerNativeInstall;
            }

            el.classList.add('is-open');
        }

        async function triggerNativeInstall() {
            const prompt = window.__toappPwaPrompt;
            if (!prompt) {
                openModal(isIos ? 'ios' : 'android-manual');
                return;
            }

            prompt.prompt();
            const choice = await prompt.userChoice.catch(function() {
                return { outcome: 'dismissed' };
            });

            window.__toappPwaPrompt = null;
            closeModal();
            closeBanner();

            if (choice.outcome === 'accepted') {
                toast('success', @json(__('To-App is being added to your home screen.')));
            }
        }

        async function handleInstallClick() {
            if (isStandalone) {
                toast('success', @json(__('To-App is already on your home screen.')));
                return;
            }

            if (!window.isSecureContext) {
                toast('error', @json(__('Install requires HTTPS. Open https://to-app.com on your phone.')));
                return;
            }

            if (window.__toappPwaPrompt) {
                await triggerNativeInstall();
                return;
            }

            if (isIos) {
                openModal('ios');
                return;
            }

            openModal('android-manual');
        }

        function bindButtons() {
            document.querySelectorAll('[data-mobile-shortcut]').forEach(function(button) {
                if (button.dataset.pwaBound === '1') return;
                button.dataset.pwaBound = '1';
                button.addEventListener('click', handleInstallClick);
            });
        }

        document.addEventListener('toapp-pwa-ready', function() {
            bindButtons();
            setTimeout(maybeShowBanner, 1800);
        });
        document.addEventListener('toapp-pwa-installed', function() {
            closeModal();
            closeBanner();
            toast('success', @json(__('To-App was added to your home screen.')));
        });

        document.addEventListener('DOMContentLoaded', function() {
            bindButtons();
            setTimeout(maybeShowBanner, 2200);
        });
        bindButtons();
    })();
</script>
