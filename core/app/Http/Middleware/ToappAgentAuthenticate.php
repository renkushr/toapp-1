<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToappAgentAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('agent')->check()) {
            return redirect()->route('toapp.agent.login');
        }

        $agent = Auth::guard('agent')->user();

        if ((int) $agent->status !== 1) {
            Auth::guard('agent')->logout();

            return redirect()->route('toapp.agent.login')->withErrors(['username' => 'This agent account is disabled.']);
        }

        return $next($request);
    }
}
