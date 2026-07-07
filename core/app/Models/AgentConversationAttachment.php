<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentConversationAttachment extends Model
{
    protected $guarded = ['id'];

    public function message()
    {
        return $this->belongsTo(AgentConversationMessage::class, 'message_id');
    }

    public function url(): string
    {
        return asset(getFilePath('agentChat') . '/' . $this->file);
    }

    public function isImage(): bool
    {
        if ($this->mime_type && str_starts_with($this->mime_type, 'image/')) {
            return true;
        }

        return in_array(strtolower(pathinfo($this->file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp'], true);
    }
}
