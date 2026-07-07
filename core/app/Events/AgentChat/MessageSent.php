<?php

namespace App\Events\AgentChat;

use App\Models\AgentConversationMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public AgentConversationMessage $message)
    {
        $this->message->loadMissing('conversation');
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('agent.conversation.' . $this->message->conversation_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'MessageSent';
    }

    public function broadcastWith(): array
    {
        $this->message->loadMissing('attachments');
        $service = app(\App\Services\AgentChatService::class);

        return $service->payload($this->message);
    }
}
