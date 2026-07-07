@php
    $pusherKey = config('broadcasting.connections.pusher.key');
    $pusherCluster = config('broadcasting.connections.pusher.options.cluster');
    $broadcastEnabled = config('broadcasting.default') === 'pusher' && filled($pusherKey);
    $agent = auth('agent')->user();
    $unreadTotal = app(\App\Services\AgentChatService::class)->unreadCountForAgent($agent->id);
@endphp

<div class="toast-container position-fixed top-0 end-0 p-3 agent-chat-toast-container" style="z-index: 1080;">
    <div id="agentChatToast" class="toast agent-chat-toast border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="10000">
        <div class="agent-chat-toast-inner">
            <div class="agent-chat-toast-icon" aria-hidden="true">
                <i class="ti ti-bell-ringing"></i>
            </div>
            <div class="agent-chat-toast-content">
                <strong id="agentChatToastTitle" class="agent-chat-toast-title">New message</strong>
                <p id="agentChatToastBody" class="agent-chat-toast-body mb-0"></p>
                <span class="agent-chat-toast-hint">Click to open chat</span>
            </div>
            <button type="button" class="agent-chat-toast-close" data-bs-dismiss="toast" aria-label="Close">
                <i class="ti ti-x"></i>
            </button>
        </div>
    </div>
</div>

@push('script-lib')
    @if($broadcastEnabled)
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
    @endif
@endpush

@push('scripts')
<script>
(function () {
    const toastEl = document.getElementById('agentChatToast');
    const toastTitle = document.getElementById('agentChatToastTitle');
    const toastBody = document.getElementById('agentChatToastBody');
    const unreadUrl = @json(route('toapp.agent.chat.unread'));
    const csrf = @json(csrf_token());
    const agentId = @json($agent->id);
    const activeClientId = @json(request()->routeIs('toapp.agent.chat.show') ? optional(request()->route('user'))->id : null);
    let toast;
    let audioCtx = null;

    function unlockAudio() {
        if (!audioCtx) {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
        if (audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
    }

    ['click', 'keydown', 'touchstart'].forEach(function (eventName) {
        document.addEventListener(eventName, unlockAudio, { once: true, passive: true });
    });

    function playNotificationSound() {
        try {
            unlockAudio();
            if (!audioCtx) return;

            const start = audioCtx.currentTime;
            const notes = [880, 1174.66, 1318.51];

            notes.forEach(function (frequency, index) {
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(frequency, start + index * 0.12);
                gain.gain.setValueAtTime(0.0001, start + index * 0.12);
                gain.gain.exponentialRampToValueAtTime(0.22, start + index * 0.12 + 0.02);
                gain.gain.exponentialRampToValueAtTime(0.0001, start + index * 0.12 + 0.28);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start(start + index * 0.12);
                osc.stop(start + index * 0.12 + 0.3);
            });
        } catch (e) {}
    }

    function setBadge(count) {
        const label = count > 99 ? '99+' : String(count);

        document.querySelectorAll('.agent-chat-unread-count').forEach(function (el) {
            el.textContent = label;
        });

        document.querySelectorAll('.agent-chat-notify-target').forEach(function (el) {
            el.classList.toggle('has-unread', count > 0);
        });

        const sidebarBadge = document.getElementById('agentChatUnreadBadge');
        const headerBadge = document.getElementById('agentChatHeaderBadge');

        [sidebarBadge, headerBadge].forEach(function (badge) {
            if (!badge) return;
            badge.classList.toggle('d-none', count <= 0);
        });

        document.querySelectorAll('.agent-chat-sidebar-pill').forEach(function (pill) {
            pill.classList.toggle('d-none', count <= 0);
        });
    }

    function pulseNotification() {
        document.querySelectorAll('.agent-chat-notify-target').forEach(function (el) {
            el.classList.remove('is-pulsing');
            void el.offsetWidth;
            el.classList.add('is-pulsing');
            window.setTimeout(function () {
                el.classList.remove('is-pulsing');
            }, 2600);
        });
    }

    function showToast(payload) {
        if (!toastEl || !window.bootstrap) return;

        toastTitle.textContent = payload.user_name || 'New client message';
        toastBody.textContent = payload.preview || 'You have a new message waiting in chat.';
        toastEl.dataset.chatUrl = payload.chat_url || '';
        toastEl.style.cursor = payload.chat_url ? 'pointer' : 'default';
        toast = toast || bootstrap.Toast.getOrCreateInstance(toastEl, { delay: 10000 });
        toast.show();

        playNotificationSound();
        pulseNotification();

        if (window.Notification && Notification.permission === 'granted') {
            new Notification(payload.user_name || 'New client message', {
                body: payload.preview || 'Open chat to reply.',
                icon: @json(asset('assets/global/img/toapp-icon.png')),
            });
        }
    }

    toastEl?.addEventListener('click', function (event) {
        if (event.target.closest('[data-bs-dismiss="toast"]')) return;
        const chatUrl = toastEl.dataset.chatUrl;
        if (chatUrl) window.location.href = chatUrl;
    });

    async function refreshUnread() {
        try {
            const res = await fetch(unreadUrl, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();
            setBadge(data.unread_total || 0);
        } catch (e) {}
    }

    setBadge(@json($unreadTotal));

    if (window.Notification && Notification.permission === 'default') {
        Notification.requestPermission().catch(function () {});
    }

    @if($broadcastEnabled)
    if (typeof Echo !== 'undefined') {
        const echo = new Echo({
            broadcaster: 'pusher',
            key: @json($pusherKey),
            cluster: @json($pusherCluster),
            encrypted: true,
            authEndpoint: @json(url('/agent/broadcasting/auth')),
            auth: { headers: { 'X-CSRF-TOKEN': csrf } },
        });

        echo.private('agent.inbox.' + agentId)
            .listen('.InboxUpdated', function (payload) {
                const previous = parseInt(document.querySelector('.agent-chat-unread-count')?.textContent || '0', 10) || 0;
                setBadge(payload.unread_total || 0);

                if (payload.user_id && payload.user_id === activeClientId) {
                    return;
                }

                if ((payload.unread_total || 0) > previous) {
                    showToast(payload);
                }
            });
    } else {
        setInterval(refreshUnread, 30000);
    }
    @else
    setInterval(refreshUnread, 30000);
    @endif
})();
</script>
@endpush
