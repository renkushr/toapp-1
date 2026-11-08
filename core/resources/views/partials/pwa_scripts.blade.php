@php
    $pwaServiceWorker = $pwaServiceWorker ?? asset('service-worker.js');
    $pwaScope = $pwaScope ?? '/';
    $pwaInstallUi = $pwaInstallUi ?? true;
@endphp
<script>
    window.__toappPwaPrompt = null;
    window.__toappPwaInstallUi = @json((bool) $pwaInstallUi);
    window.addEventListener('beforeinstallprompt', function(event) {
        // Portals: capture silently (no custom install/download UI on page).
        // User app: capture and surface via Install App controls.
        event.preventDefault();
        window.__toappPwaPrompt = event;
        if (window.__toappPwaInstallUi) {
            document.dispatchEvent(new CustomEvent('toapp-pwa-ready'));
        }
    });
    window.addEventListener('appinstalled', function() {
        window.__toappPwaPrompt = null;
        try {
            localStorage.setItem('toapp_pwa_installed', '1');
        } catch (e) {}
        document.dispatchEvent(new CustomEvent('toapp-pwa-installed'));
    });

    (function markStandalone() {
        var standalone = window.matchMedia('(display-mode: standalone)').matches
            || window.navigator.standalone === true
            || document.referrer.indexOf('android-app://') === 0;
        if (standalone) {
            document.documentElement.classList.add('toapp-standalone');
            document.addEventListener('DOMContentLoaded', function() {
                document.body.classList.add('toapp-standalone');
            });
        }
    })();
</script>
<script>
    (function() {
        if (!('serviceWorker' in navigator) || !window.isSecureContext) {
            return;
        }

        window.addEventListener('load', function() {
            navigator.serviceWorker.register(@json($pwaServiceWorker), { scope: @json($pwaScope) })
                .then(function(registration) {
                    window.__toappSwRegistration = registration;
                    if (registration.waiting) {
                        registration.waiting.postMessage({ type: 'SKIP_WAITING' });
                    }
                })
                .catch(function() {});
        });
    })();
</script>
