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

class UserInboxUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public AgentConversationMessage $message)
    {
        $this->message->loadMissing(['conversation.user', 'conversation.agent', 'attachments']);
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.inbox.' . $this->message->conversation->user_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'InboxUpdated';
    }

    public function broadcastWhen(): bool
    {
        return (bool) $this->message->conversation?->user_id;
    }

    public function broadcastWith(): array
    {
        $agent = $this->message->conversation->agent;
        $preview = trim((string) $this->message->message);

        if ($preview === '' && $this->message->attachments->isNotEmpty()) {
            $preview = '📎 ' . $this->message->attachments->first()->original_name;
        }

        $service = app(AgentChatService::class);
        $user = $this->message->conversation->user;

        return [
            'conversation_id' => $this->message->conversation_id,
            'message_id' => $this->message->id,
            'agent_id' => $agent?->id,
            'agent_name' => $agent?->name ?: 'Your agent',
            'preview' => Str::limit($preview, 120),
            'unread_total' => $service->unreadCountForUser((int) $this->message->conversation->user_id),
            'chat_url' => $user ? route('user.agent.chat') : null,
        ];
    }
}
