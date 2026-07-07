<?php

namespace App\Http\Controllers\ToappAgent;

use App\Constants\Status;
use App\Models\AgentConversation;
use App\Models\Investment;
use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $agent = Auth::guard('agent')->user();
        $agentId = $agent->id;

        $usersQuery = User::where('agent_id', $agentId);

        return view('toapp_agent.dashboard', [
            'pageTitle' => 'Agent Dashboard',
            'agent' => $agent,
            'stats' => [
                'assigned_users' => (clone $usersQuery)->count(),
                'zip_codes' => $agent->zipCodes()->count(),
                'active_products' => Investment::whereIn('user_id', (clone $usersQuery)->select('id'))
                    ->where('status', Status::RUNNING)
                    ->count(),
                'open_chats' => AgentConversation::where('agent_id', $agentId)->count(),
            ],
            'recentUsers' => User::where('agent_id', $agentId)
                ->latest('id')
                ->limit(8)
                ->get(),
        ]);
    }
}
