<script>
    window.__toappPwaPrompt = null;
    window.addEventListener('beforeinstallprompt', function(event) {
        event.preventDefault();
        window.__toappPwaPrompt = event;
        document.dispatchEvent(new CustomEvent('toapp-pwa-ready'));
    });
    window.addEventListener('appinstalled', function() {
        window.__toappPwaPrompt = null;
        document.dispatchEvent(new CustomEvent('toapp-pwa-installed'));
    });
</script>
<script>
    (function() {
        if (!('serviceWorker' in navigator) || !window.isSecureContext) {
            return;
        }

        window.addEventListener('load', function() {
            navigator.serviceWorker.register("{{ asset('service-worker.js') }}", { scope: '/' })
                .then(function(registration) {
                    window.__toappSwRegistration = registration;
                })
                .catch(function() {});
        });
    })();
</script>
