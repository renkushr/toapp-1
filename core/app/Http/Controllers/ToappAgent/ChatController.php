<?php

namespace App\Http\Controllers\ToappAgent;

use App\Models\AgentConversation;
use App\Models\User;
use App\Services\AgentChatService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct(private AgentChatService $chatService)
    {
    }

    public function index()
    {
        $agent = Auth::guard('agent')->user();

        $conversations = AgentConversation::with(['user', 'latestMessage.attachments'])
            ->forAgentTerritory($agent)
            ->latest('last_message_at')
            ->paginate(20);

        $unreadByConversation = $this->chatService->unreadCountsByConversation($agent);

        return view('toapp_agent.chat.index', [
            'pageTitle' => 'Client Chat',
            'agent' => $agent,
            'conversations' => $conversations,
            'unreadByConversation' => $unreadByConversation,
        ]);
    }

    public function show(User $user)
    {
        $agent = Auth::guard('agent')->user();
        abort_unless($agent->canAccessUser($user), 403);

        $conversation = AgentConversation::findOrCreateFor($agent->id, $user->id);

        $messages = $conversation->messages()->with('attachments')->oldest('id')->get();

        $this->chatService->markReadForRecipient($conversation, 'agent');

        return view('toapp_agent.chat.show', [
            'pageTitle' => 'Chat with ' . $user->firstname,
            'agent' => $agent,
            'user' => $user,
            'conversation' => $conversation,
            'messages' => $messages,
            'pusherAuthEndpoint' => url('/agent/broadcasting/auth'),
        ]);
    }

    public function send(Request $request, User $user)
    {
        $agent = Auth::guard('agent')->user();
        abort_unless($agent->canAccessUser($user), 403);

        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:5000'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file'],
        ]);

        $this->chatService->validateOutgoing($validated['message'] ?? null, $request->file('attachments'));

        $conversation = AgentConversation::findOrCreateFor($agent->id, $user->id);

        $message = $this->chatService->send(
            $conversation,
            'agent',
            $agent->id,
            $validated['message'] ?? null,
            $request->file('attachments', [])
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $this->chatService->payload($message),
            ]);
        }

        return back()->with('status', 'Message sent.');
    }

    public function messages(User $user)
    {
        $agent = Auth::guard('agent')->user();
        abort_unless($agent->canAccessUser($user), 403);

        $conversation = AgentConversation::forAgentTerritory($agent)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $since = request()->query('since');

        $query = $conversation->messages()->with('attachments')->oldest('id');

        if ($since) {
            $query->where('id', '>', (int) $since);
        }

        $messages = $query->get()->map(fn ($message) => $this->chatService->payload($message));

        $this->chatService->markReadForRecipient($conversation, 'agent');

        return response()->json(['messages' => $messages]);
    }

    public function unreadSummary()
    {
        $agent = Auth::guard('agent')->user();

        return response()->json([
            'unread_total' => $this->chatService->unreadCountForAgent($agent),
            'by_conversation' => $this->chatService->unreadCountsByConversation($agent),
        ]);
    }
}
