<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentConversationMessage extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function conversation()
    {
        return $this->belongsTo(AgentConversation::class, 'conversation_id');
    }

    public function attachments()
    {
        return $this->hasMany(AgentConversationAttachment::class, 'message_id');
    }

    public function isFromAgent(): bool
    {
        return $this->sender_type === 'agent';
    }

    public function isFromUser(): bool
    {
        return $this->sender_type === 'user';
    }
}
