<?php

use App\Models\Agent;
use App\Models\AgentConversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('agent.conversation.{conversationId}', function ($user, int $conversationId) {
    $conversation = AgentConversation::with('user')->find($conversationId);

    if (!$conversation) {
        return false;
    }

    if ($user instanceof User) {
        return (int) $conversation->user_id === (int) $user->id;
    }

    if ($user instanceof Agent) {
        return $conversation->user && $user->canAccessUser($conversation->user);
    }

    $agent = Auth::guard('agent')->user();
    if ($agent instanceof Agent) {
        return $conversation->user && $agent->canAccessUser($conversation->user);
    }

    $member = Auth::guard('web')->user();
    if ($member instanceof User) {
        return (int) $conversation->user_id === (int) $member->id;
    }

    return false;
});

Broadcast::channel('agent.inbox.{agentId}', function ($user, int $agentId) {
    if ($user instanceof Agent) {
        return (int) $user->id === $agentId;
    }

    $agent = Auth::guard('agent')->user();

    return $agent instanceof Agent && (int) $agent->id === $agentId;
});

Broadcast::channel('user.inbox.{userId}', function ($user, int $userId) {
    if ($user instanceof User) {
        return (int) $user->id === $userId;
    }

    $member = Auth::guard('web')->user();

    return $member instanceof User && (int) $member->id === $userId;
});

Broadcast::channel('ticket.{id}', function ($ticketId, $message) {
    $ticket = \App\Models\SupportTicket::find($message->support_ticket_id ?? null);

    return $ticket && ($ticketId == $ticket->user_id || auth('admin')->user());
});
