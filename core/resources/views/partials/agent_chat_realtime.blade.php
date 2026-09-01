@php
    $pusherKey = config('broadcasting.connections.pusher.key');
    $pusherCluster = config('broadcasting.connections.pusher.options.cluster');
    $broadcastEnabled = config('broadcasting.default') === 'pusher' && filled($pusherKey);
    $theme = $theme ?? 'agent';
    $ownSender = $ownSender ?? ($theme === 'agent' ? 'agent' : 'user');
    $ownLabel = $ownLabel ?? 'You';
    $peerLabel = $peerLabel ?? 'AG';
    $scriptStack = in_array($theme, ['agent', 'checkinsure'], true) ? 'scripts' : 'script';
@endphp

@push($scriptStack)
<script>
(function () {
    const box = document.getElementById(@json($boxId));
    const form = document.getElementById(@json($formId));
    const pollUrl = @json($pollUrl);
    const csrf = @json(csrf_token());
    const ownSender = @json($ownSender);
    const theme = @json($theme);
    const ownLabel = @json($ownLabel);
    const peerLabel = @json($peerLabel);
    let lastId = @json($lastMessageId);

    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function renderAttachments(attachments, variant) {
        if (!attachments || !attachments.length) return '';

        const cssVariant = variant || (theme === 'checkinsure' ? 'ci' : (theme === 'user' ? 'app' : 'ta'));
        let html = '<div class="chat-attachments ' + cssVariant + '">';

        attachments.forEach(function (attachment) {
            if (attachment.is_image) {
                html += '<a class="chat-attachment-image" href="' + escapeHtml(attachment.url) + '" target="_blank" rel="noopener">' +
                    '<img src="' + escapeHtml(attachment.url) + '" alt="' + escapeHtml(attachment.name) + '" loading="lazy">' +
                    '</a>';
            } else {
                html += '<a class="chat-attachment-file" href="' + escapeHtml(attachment.url) + '" target="_blank" rel="noopener" download>' +
                    '<span class="chat-attachment-icon" aria-hidden="true">📄</span>' +
                    '<span class="chat-attachment-name">' + escapeHtml(attachment.name) + '</span>' +
                    '</a>';
            }
        });

        html += '</div>';
        return html;
    }

    function appendMessage(msg) {
        if (!box || document.querySelector('[data-id="' + msg.id + '"]')) return;

        const emptyState = box.querySelector('.ci-chat-empty, .ta-chat-empty');
        if (emptyState) emptyState.remove();

        const bodyHtml = msg.body ? '<p class="ci-chat-text">' + escapeHtml(msg.body) + '</p>' : '';
        const attachmentsHtml = renderAttachments(msg.attachments || []);
        const timeHtml = '<time class="ci-chat-time">' + escapeHtml(msg.created_at) + '</time>';
        const el = document.createElement('div');
        el.dataset.id = msg.id;

        if (theme === 'agent') {
            const isOwn = msg.sender_type === ownSender;
            el.className = 'ta-chat-msg ' + (isOwn ? 'ta-chat-msg--own' : 'ta-chat-msg--peer');
            const agentBody = msg.body ? '<p class="ta-chat-text">' + escapeHtml(msg.body).replace(/\n/g, '<br>') + '</p>' : '';
            el.innerHTML =
                '<div class="ta-chat-avatar">' + (isOwn ? ownLabel : peerLabel) + '</div>' +
                '<div class="ta-chat-bubble">' +
                    agentBody + renderAttachments(msg.attachments || [], 'ta') +
                    '<time class="ta-chat-time">' + escapeHtml(msg.created_at) + '</time>' +
                '</div>';
        } else if (theme === 'checkinsure') {
            const isOwn = msg.sender_type === ownSender;
            el.className = 'ci-chat-msg ' + (isOwn ? 'ci-chat-msg--own' : 'ci-chat-msg--peer');
            el.innerHTML =
                '<div class="ci-chat-avatar">' + (isOwn ? ownLabel : peerLabel) + '</div>' +
                '<div class="ci-chat-bubble">' +
                    bodyHtml + attachmentsHtml + timeHtml +
                '</div>';
        } else {
            el.className = 'app-list-item';
            el.style.flexDirection = 'column';
            el.style.alignItems = msg.sender_type === ownSender ? 'flex-end' : 'flex-start';
            const isOwn = msg.sender_type === ownSender;
            el.innerHTML = '<div style="background:' + (isOwn ? '#1268f3' : '#f1f5f9') + '; color:' + (isOwn ? '#fff' : '#0f172a') + '; padding:10px 14px; border-radius:12px; max-width:85%;">' +
                (msg.body ? escapeHtml(msg.body) : '') + renderAttachments(msg.attachments || []) +
                '<small style="display:block; opacity:.7; margin-top:4px;">' + escapeHtml(msg.created_at) + '</small></div>';
        }

        box.appendChild(el);
        box.scrollTop = box.scrollHeight;
        lastId = Math.max(lastId, msg.id);
    }

    function collectAttachmentInputs() {
        const ids = ['chatAttachments', 'chatImageInput', 'chatFileInput'];
        const inputs = [];

        ids.forEach(function (id) {
            const input = document.getElementById(id);
            if (input) inputs.push(input);
        });

        if (!inputs.length && form) {
            form.querySelectorAll('input[type="file"][name="attachments[]"]').forEach(function (input) {
                inputs.push(input);
            });
        }

        return inputs;
    }

    function collectSelectedFiles() {
        const files = [];
        collectAttachmentInputs().forEach(function (input) {
            Array.from(input.files || []).forEach(function (file) {
                files.push(file);
            });
        });
        return files;
    }

    function clearAttachmentPreview() {
        const preview = document.getElementById('chatAttachmentPreview');
        collectAttachmentInputs().forEach(function (input) {
            input.value = '';
        });
        if (preview) preview.innerHTML = '';
    }

    function bindAttachmentPreview() {
        const preview = document.getElementById('chatAttachmentPreview');
        if (!preview) return;

        collectAttachmentInputs().forEach(function (input) {
            input.addEventListener('change', renderAttachmentPreview);
        });
    }

    function renderAttachmentPreview() {
        const preview = document.getElementById('chatAttachmentPreview');
        if (!preview) return;

        preview.innerHTML = '';
        const files = collectSelectedFiles();

        files.forEach(function (file) {
            if (file.type && file.type.startsWith('image/')) {
                const wrap = document.createElement('div');
                wrap.className = 'chat-attachment-preview-item';
                const img = document.createElement('img');
                img.alt = file.name;
                img.src = URL.createObjectURL(file);
                wrap.appendChild(img);
                preview.appendChild(wrap);
                return;
            }

            const chip = document.createElement('span');
            chip.className = 'chat-attachment-chip';
            chip.textContent = file.name;
            preview.appendChild(chip);
        });
    }

    if (box) box.scrollTop = box.scrollHeight;
    bindAttachmentPreview();

    form?.addEventListener('submit', async function (e) {
        e.preventDefault();
        const fd = new FormData(form);
        const files = collectSelectedFiles();

        fd.delete('attachments[]');
        files.forEach(function (file) {
            fd.append('attachments[]', file);
        });

        const res = await fetch(form.action, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: fd,
        });

        if (!res.ok) {
            const errorData = await res.json().catch(function () { return {}; });
            const message = errorData.message || (errorData.errors ? Object.values(errorData.errors).flat().join(' ') : 'Could not send message.');
            alert(message);
            return;
        }

        const data = await res.json();
        if (data.message) appendMessage(data.message);
        form.reset();
        clearAttachmentPreview();
        const textarea = form.querySelector('textarea');
        if (textarea) textarea.style.height = 'auto';
    });

    @if($broadcastEnabled)
    if (typeof Echo !== 'undefined') {
        const echo = new Echo({
            broadcaster: 'pusher',
            key: @json($pusherKey),
            cluster: @json($pusherCluster),
            encrypted: true,
            authEndpoint: @json($pusherAuthEndpoint),
            auth: { headers: { 'X-CSRF-TOKEN': csrf } },
        });

        echo.private('agent.conversation.' + @json($conversationId))
            .listen('.MessageSent', function (payload) {
                appendMessage(payload);
            });
    }
    @else
    setInterval(async function () {
        try {
            const res = await fetch(pollUrl + '?since=' + lastId, { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            (data.messages || []).forEach(appendMessage);
        } catch (e) {}
    }, 5000);
    @endif
})();
</script>
@endpush
