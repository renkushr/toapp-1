<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AgentConversation;
use App\Services\AgentChatService;
use Illuminate\Http\Request;

class AgentChatController extends Controller
{
    public function __construct(private AgentChatService $chatService)
    {
    }

    public function index()
    {
        $user = auth()->user();

        if (!$user->agent_id) {
            return view('Template::user.agent_chat', [
                'pageTitle' => 'My Agent',
                'user' => $user,
                'agent' => null,
                'conversation' => null,
                'messages' => collect(),
                'pusherAuthEndpoint' => url('/broadcasting/auth'),
            ]);
        }

        $agent = $user->agent;

        if (!$agent || !(bool) $agent->status) {
            return view('Template::user.agent_chat', [
                'pageTitle' => 'My Agent',
                'user' => $user,
                'agent' => null,
                'conversation' => null,
                'messages' => collect(),
                'pusherAuthEndpoint' => url('/broadcasting/auth'),
            ]);
        }

        $conversation = AgentConversation::findOrCreateFor($agent->id, $user->id);

        $messages = $conversation->messages()->with('attachments')->oldest('id')->get();

        $this->chatService->markReadForRecipient($conversation, 'user');

        return view('Template::user.agent_chat', [
            'pageTitle' => 'My Agent',
            'user' => $user,
            'agent' => $agent,
            'conversation' => $conversation,
            'messages' => $messages,
            'pusherAuthEndpoint' => url('/broadcasting/auth'),
        ]);
    }

    public function send(Request $request)
    {
        $user = auth()->user();
        abort_unless($user->agent_id, 403);

        $agent = $user->agent;
        abort_unless($agent && (int) $agent->status === 1, 403);

        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:5000'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file'],
        ]);

        $this->chatService->validateOutgoing($validated['message'] ?? null, $request->file('attachments'));

        $conversation = AgentConversation::findOrCreateFor($agent->id, $user->id);

        $message = $this->chatService->send(
            $conversation,
            'user',
            $user->id,
            $validated['message'] ?? null,
            $request->file('attachments', [])
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $this->chatService->payload($message),
            ]);
        }

        return back()->withNotify([['success', 'Message sent to your agent.']]);
    }

    public function messages()
    {
        $user = auth()->user();
        abort_unless($user->agent_id, 403);

        $conversation = AgentConversation::where('agent_id', $user->agent_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $since = request()->query('since');

        $query = $conversation->messages()->with('attachments')->oldest('id');

        if ($since) {
            $query->where('id', '>', (int) $since);
        }

        $messages = $query->get()->map(fn ($message) => $this->chatService->payload($message));

        $this->chatService->markReadForRecipient($conversation, 'user');

        return response()->json(['messages' => $messages]);
    }
}
