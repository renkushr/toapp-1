<?php

namespace App\Events\AgentChat;

use App\Models\AgentConversationMessage;
use App\Services\AgentChatService;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class AgentInboxUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public AgentConversationMessage $message)
    {
        $this->message->loadMissing(['conversation.user', 'attachments']);
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('agent.inbox.' . $this->message->conversation->agent_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'InboxUpdated';
    }

    public function broadcastWith(): array
    {
        $user = $this->message->conversation->user;
        $preview = trim((string) $this->message->message);

        if ($preview === '' && $this->message->attachments->isNotEmpty()) {
            $preview = '📎 ' . $this->message->attachments->first()->original_name;
        }

        $service = app(AgentChatService::class);

        return [
            'conversation_id' => $this->message->conversation_id,
            'message_id' => $this->message->id,
            'user_id' => $user?->id,
            'user_name' => trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? '')) ?: ($user->email ?? 'Client'),
            'preview' => Str::limit($preview, 120),
            'unread_total' => $service->unreadCountForAgent((int) $this->message->conversation->agent_id),
            'chat_url' => $user ? route('toapp.agent.chat.show', $user) : null,
        ];
    }
}
