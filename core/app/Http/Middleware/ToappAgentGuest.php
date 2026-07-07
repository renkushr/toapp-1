<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToappAgentGuest
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('agent')->check()) {
            return redirect()->route('toapp.agent.dashboard');
        }

        return $next($request);
    }
}
