@once
    @push('style')
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
                background: linear-gradient(135deg, #6f39ff, #14c7d6);
                color: #fff;
                font-weight: 700;
                box-shadow: 0 12px 28px rgba(89, 54, 242, 0.22);
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
                box-shadow: 0 8px 20px rgba(89, 54, 242, 0.18);
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
                background: rgba(89, 54, 242, 0.12);
                color: #5936f2;
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
                background: linear-gradient(135deg, #6f39ff, #14c7d6);
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

                .pwa-install-modal {
                    display: none !important;
                }
            }
        </style>
    @endpush

    @push('script')
        <script>
            (function() {
                "use strict";

                const iconUrl = @json(asset('assets/global/img/pwa/apple-touch-icon.png'));
                const isIos = /iphone|ipad|ipod/i.test(window.navigator.userAgent);
                const isAndroid = /android/i.test(window.navigator.userAgent);
                const isStandalone = window.matchMedia('(display-mode: standalone)').matches
                    || window.navigator.standalone === true;

                let modal = null;

                function toast(type, message) {
                    if (typeof notify === 'function') {
                        notify(type, message);
                    }
                }

                function ensureModal() {
                    if (modal) return modal;

                    modal = document.createElement('div');
                    modal.className = 'pwa-install-modal';
                    modal.innerHTML = `
                        <div class="pwa-install-modal__sheet" role="dialog" aria-modal="true" aria-labelledby="pwa-install-title">
                            <div class="pwa-install-modal__head">
                                <img class="pwa-install-modal__icon" src="${iconUrl}" alt="To-app">
                                <div>
                                    <h3 class="pwa-install-modal__title" id="pwa-install-title">Add To-app to Home Screen</h3>
                                    <p class="pwa-install-modal__text" data-pwa-install-subtitle>Open like a real app from your phone.</p>
                                </div>
                            </div>
                            <ol class="pwa-install-steps" data-pwa-install-steps></ol>
                            <div class="pwa-install-modal__actions">
                                <button type="button" class="pwa-install-modal__secondary" data-pwa-install-close>Not now</button>
                                <button type="button" class="pwa-install-modal__primary" data-pwa-install-action>Install</button>
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

                function closeModal() {
                    if (modal) {
                        modal.classList.remove('is-open');
                    }
                }

                function openModal(mode) {
                    const el = ensureModal();
                    const steps = el.querySelector('[data-pwa-install-steps]');
                    const action = el.querySelector('[data-pwa-install-action]');
                    const subtitle = el.querySelector('[data-pwa-install-subtitle]');

                    if (mode === 'ios') {
                        subtitle.textContent = 'Use Safari Share menu to add the app icon.';
                        steps.innerHTML = `
                            <li><strong>1</strong><span>Tap the <b>Share</b> button at the bottom of Safari.</span></li>
                            <li><strong>2</strong><span>Scroll and tap <b>Add to Home Screen</b>.</span></li>
                            <li><strong>3</strong><span>Tap <b>Add</b> in the top-right corner.</span></li>
                        `;
                        action.textContent = 'Got it';
                        action.onclick = closeModal;
                    } else if (mode === 'android-manual') {
                        subtitle.textContent = 'Use your browser menu to install the app.';
                        steps.innerHTML = `
                            <li><strong>1</strong><span>Tap the browser menu <b>⋮</b> (top-right).</span></li>
                            <li><strong>2</strong><span>Choose <b>Install app</b> or <b>Add to Home screen</b>.</span></li>
                            <li><strong>3</strong><span>Confirm to add the To-app icon.</span></li>
                        `;
                        action.textContent = 'Got it';
                        action.onclick = closeModal;
                    } else {
                        subtitle.textContent = 'Install To-app directly from this prompt.';
                        steps.innerHTML = `
                            <li><strong>1</strong><span>Tap <b>Install</b> below.</span></li>
                            <li><strong>2</strong><span>Confirm in the browser install dialog.</span></li>
                            <li><strong>3</strong><span>Open To-app from your home screen.</span></li>
                        `;
                        action.textContent = 'Install now';
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

                    if (choice.outcome === 'accepted') {
                        toast('success', 'To-app is being added to your home screen.');
                    }
                }

                async function handleInstallClick() {
                    if (isStandalone) {
                        toast('success', 'To-app is already on your home screen.');
                        return;
                    }

                    if (!window.isSecureContext) {
                        toast('error', 'Install requires HTTPS. Open https://to-app.com on your phone.');
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

                    if (isAndroid) {
                        openModal('android-manual');
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

                document.addEventListener('toapp-pwa-ready', bindButtons);
                document.addEventListener('toapp-pwa-installed', function() {
                    closeModal();
                    toast('success', 'To-app was added to your home screen.');
                });

                document.addEventListener('DOMContentLoaded', bindButtons);
                bindButtons();
            })();
        </script>
    @endpush
@endonce

<div class="mobile-shortcut">
    <button class="mobile-shortcut__button" type="button" data-mobile-shortcut>
        <i class="las la-mobile-alt"></i>
        @lang('Add to Home Screen')
    </button>
</div>
