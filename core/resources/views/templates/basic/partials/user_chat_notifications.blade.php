@php
    if (!auth()->check()) {
        return;
    }
    $pusherKey = config('broadcasting.connections.pusher.key');
    $pusherCluster = config('broadcasting.connections.pusher.options.cluster');
    $broadcastEnabled = config('broadcasting.default') === 'pusher' && filled($pusherKey);
    $user = auth()->user();
    $unreadTotal = app(\App\Services\AgentChatService::class)->unreadCountForUser($user);
    $latestInsuranceChatApplication = \App\Models\InsuranceApplication::query()
        ->where('user_id', $user->id)
        ->whereIn('status', [
            \App\Models\InsuranceApplication::STATUS_CHATTING,
            \App\Models\InsuranceApplication::STATUS_PAYMENT_PENDING,
            \App\Models\InsuranceApplication::STATUS_CONFIRMED,
        ])
        ->latest('id')
        ->first();
    $agentChatUrl = $latestInsuranceChatApplication
        ? route('user.plans.chat', $latestInsuranceChatApplication)
        : route('user.agent.chat');
    $onChatPage = request()->routeIs('user.agent.chat', 'user.plans.chat');
@endphp

<div class="toast-container position-fixed top-0 end-0 p-3 user-chat-toast-container" style="z-index: 1080;">
    <div id="userChatToast" class="toast user-chat-toast border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="10000">
        <div class="user-chat-toast-inner">
            <div class="user-chat-toast-icon" aria-hidden="true"><i class="las la-comments"></i></div>
            <div class="user-chat-toast-content">
                <strong id="userChatToastTitle" class="d-block">@lang('New message')</strong>
                <p id="userChatToastBody" class="mb-0 small text-muted"></p>
            </div>
            <button type="button" class="btn-close ms-2" data-bs-dismiss="toast" aria-label="@lang('Close')"></button>
        </div>
    </div>
</div>

@push('script-lib')
    @if($broadcastEnabled)
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
    @endif
@endpush

@push('script')
<script>
(function () {
    const toastEl = document.getElementById('userChatToast');
    const toastTitle = document.getElementById('userChatToastTitle');
    const toastBody = document.getElementById('userChatToastBody');
    const unreadUrl = @json(route('user.agent.chat.unread'));
    const chatUrl = @json($agentChatUrl);
    const userId = @json($user->id);
    const onChatPage = @json($onChatPage);
    const csrf = @json(csrf_token());
    let toast;

    function setBadge(count) {
        count = Math.max(0, parseInt(count, 10) || 0);
        const label = count > 99 ? '99+' : String(count);

        document.querySelectorAll('.user-chat-unread-count').forEach(function (el) {
            el.textContent = label;
        });

        document.querySelectorAll('.user-chat-notify-target').forEach(function (el) {
            el.classList.toggle('has-unread', count > 0);
        });

        document.querySelectorAll('.user-chat-unread-badge').forEach(function (badge) {
            badge.classList.toggle('d-none', count <= 0);
            badge.textContent = label;
        });
    }

    async function refreshUnread() {
        try {
            const res = await fetch(unreadUrl, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
            });
            if (!res.ok) return;
            const data = await res.json();
            setBadge(data.unread_total || 0);
        } catch (e) {}
    }

    window.userChatRefreshUnread = refreshUnread;
    window.userChatSetUnreadBadge = setBadge;

    function showToast(payload) {
        if (!toastEl || !window.bootstrap) return;
        toastTitle.textContent = payload.agent_name || 'Message from your agent';
        toastBody.textContent = payload.preview || 'You have a new chat message.';
        toastEl.dataset.chatUrl = payload.chat_url || chatUrl;
        toastEl.style.cursor = 'pointer';
        toast = toast || bootstrap.Toast.getOrCreateInstance(toastEl, { delay: 10000 });
        toast.show();
    }

    toastEl?.addEventListener('click', function (event) {
        if (event.target.closest('[data-bs-dismiss="toast"]')) return;
        window.location.href = toastEl.dataset.chatUrl || chatUrl;
    });

    setBadge(@json($unreadTotal));
    refreshUnread();

    @if($broadcastEnabled)
    if (typeof Echo !== 'undefined') {
        const echo = new Echo({
            broadcaster: 'pusher',
            key: @json($pusherKey),
            cluster: @json($pusherCluster),
            encrypted: true,
            authEndpoint: @json(url('/broadcasting/auth')),
            auth: { headers: { 'X-CSRF-TOKEN': csrf } },
        });

        echo.private('user.inbox.' + userId)
            .listen('.InboxUpdated', function (payload) {
                const previous = parseInt(document.querySelector('.user-chat-unread-count')?.textContent || '0', 10) || 0;
                const nextTotal = payload.unread_total || 0;

                if (onChatPage) {
                    if (typeof window.agentChatMarkActiveRead === 'function') {
                        window.agentChatMarkActiveRead().finally(refreshUnread);
                    } else {
                        refreshUnread();
                    }
                    return;
                }

                setBadge(nextTotal);
                if (nextTotal > previous) {
                    showToast(payload);
                }
            });

        setInterval(refreshUnread, 60000);
    } else {
        setInterval(refreshUnread, 15000);
    }
    @else
    setInterval(refreshUnread, 15000);
    @endif

    document.addEventListener('visibilitychange', function () {
        if (!document.hidden) refreshUnread();
    });
})();
</script>
@endpush
