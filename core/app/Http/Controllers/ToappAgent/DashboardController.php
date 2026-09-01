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

        $usersQuery = User::forAgentTerritory($agent);

        return view('toapp_agent.dashboard', [
            'pageTitle' => 'Agent Dashboard',
            'agent' => $agent,
            'stats' => [
                'assigned_users' => (clone $usersQuery)->count(),
                'zip_codes' => $agent->zipCodes()->count(),
                'active_products' => Investment::whereIn('user_id', (clone $usersQuery)->select('id'))
                    ->where('status', Status::RUNNING)
                    ->count(),
                'open_chats' => AgentConversation::forAgentTerritory($agent)->count(),
            ],
            'recentUsers' => User::forAgentTerritory($agent)
                ->latest('id')
                ->limit(8)
                ->get(),
        ]);
    }
}
