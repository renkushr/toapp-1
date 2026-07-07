<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentConversation extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(AgentConversationMessage::class, 'conversation_id');
    }

    public function latestMessage()
    {
        return $this->hasOne(AgentConversationMessage::class, 'conversation_id')->latestOfMany();
    }

    public static function findOrCreateFor(int $agentId, int $userId): self
    {
        return static::firstOrCreate(
            ['agent_id' => $agentId, 'user_id' => $userId],
            ['last_message_at' => now()]
        );
    }
}
