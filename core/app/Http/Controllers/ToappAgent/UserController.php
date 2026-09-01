<?php

namespace App\Http\Controllers\ToappAgent;

use App\Models\Investment;
use App\Models\User;
use App\Support\UsZip;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $agent = Auth::guard('agent')->user();

        $users = User::query()
            ->forAgentTerritory($agent)
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($inner) use ($search) {
                    $inner->where('firstname', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('mobile', 'like', "%{$search}%")
                        ->orWhere('zipcode', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('zip'), function ($query) use ($request) {
                $zip = UsZip::normalize($request->string('zip')->toString());
                if ($zip) {
                    $query->where('zipcode', 'like', $zip . '%');
                }
            })
            ->withCount(['investments'])
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('toapp_agent.users.index', [
            'pageTitle' => 'My Clients',
            'agent' => $agent,
            'users' => $users,
        ]);
    }

    public function show(User $user)
    {
        $agent = Auth::guard('agent')->user();
        abort_unless($agent->canAccessUser($user), 403);

        $investments = Investment::with('plan')
            ->where('user_id', $user->id)
            ->latest('id')
            ->get();

        return view('toapp_agent.users.show', [
            'pageTitle' => $user->firstname . ' ' . $user->lastname,
            'agent' => $agent,
            'user' => $user,
            'investments' => $investments,
        ]);
    }
}
