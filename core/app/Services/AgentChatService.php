<?php

namespace App\Services;

use App\Events\AgentChat\AgentInboxUpdated;
use App\Events\AgentChat\MessageSent;
use App\Models\Agent;
use App\Models\AgentConversation;
use App\Models\AgentConversationAttachment;
use App\Models\AgentConversationMessage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class AgentChatService
{
    public const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'txt'];

    public function send(
        AgentConversation $conversation,
        string $senderType,
        int $senderId,
        ?string $body,
        array $files = []
    ): AgentConversationMessage {
        $message = AgentConversationMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => $senderType,
            'sender_id' => $senderId,
            'message' => trim((string) $body),
        ]);

        foreach ($files as $file) {
            $this->storeAttachment($message, $file);
        }

        $conversation->update(['last_message_at' => now()]);

        $message->load('attachments');

        try {
            if (config('broadcasting.default') === 'pusher' && filled(config('broadcasting.connections.pusher.key'))) {
                broadcast(new MessageSent($message))->toOthers();

                if ($senderType === 'user') {
                    broadcast(new AgentInboxUpdated($message));
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return $message;
    }

    public function storeAttachment(AgentConversationMessage $message, UploadedFile $file): AgentConversationAttachment
    {
        $path = getFilePath('agentChat');

        return AgentConversationAttachment::create([
            'message_id' => $message->id,
            'file' => fileUploader($file, $path),
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size' => (int) $file->getSize(),
        ]);
    }

    public function validateOutgoing(?string $body, ?array $files): void
    {
        $files = array_filter($files ?? []);
        $hasBody = trim((string) $body) !== '';
        $hasFiles = count($files) > 0;

        if (!$hasBody && !$hasFiles) {
            throw ValidationException::withMessages([
                'message' => 'Please enter a message or attach a file.',
            ]);
        }

        if (count($files) > 5) {
            throw ValidationException::withMessages([
                'attachments' => 'You can attach up to 5 files per message.',
            ]);
        }

        foreach ($files as $index => $file) {
            if (!$file instanceof UploadedFile) {
                continue;
            }

            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, self::ALLOWED_EXTENSIONS, true)) {
                throw ValidationException::withMessages([
                    "attachments.$index" => 'Only jpg, jpeg, png, gif, webp, pdf, doc, docx, and txt files are allowed.',
                ]);
            }

            if ($file->getSize() > 10 * 1024 * 1024) {
                throw ValidationException::withMessages([
                    "attachments.$index" => 'Each file must be 10 MB or smaller.',
                ]);
            }
        }
    }

    public function unreadCountForAgent(Agent|int $agent): int
    {
        $agent = $agent instanceof Agent ? $agent : Agent::findOrFail($agent);

        return AgentConversationMessage::query()
            ->whereHas('conversation', fn ($query) => $query->forAgentTerritory($agent))
            ->where('sender_type', 'user')
            ->whereNull('read_at')
            ->count();
    }

    public function unreadCountsByConversation(Agent|int $agent): Collection
    {
        $agent = $agent instanceof Agent ? $agent : Agent::findOrFail($agent);

        return AgentConversationMessage::query()
            ->selectRaw('conversation_id, COUNT(*) as unread_count')
            ->whereHas('conversation', fn ($query) => $query->forAgentTerritory($agent))
            ->where('sender_type', 'user')
            ->whereNull('read_at')
            ->groupBy('conversation_id')
            ->pluck('unread_count', 'conversation_id');
    }

    public function markReadForRecipient(AgentConversation $conversation, string $recipientType): void
    {
        AgentConversationMessage::where('conversation_id', $conversation->id)
            ->where('sender_type', $recipientType === 'agent' ? 'user' : 'agent')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function payload(AgentConversationMessage $message): array
    {
        $message->loadMissing('attachments');

        return [
            'id' => $message->id,
            'body' => $message->message,
            'sender_type' => $message->sender_type,
            'created_at' => $message->created_at?->format('M d, H:i'),
            'attachments' => $message->attachments->map(fn (AgentConversationAttachment $attachment) => [
                'id' => $attachment->id,
                'name' => $attachment->original_name,
                'url' => $attachment->url(),
                'is_image' => $attachment->isImage(),
                'size' => $attachment->size,
            ])->values()->all(),
        ];
    }
}
