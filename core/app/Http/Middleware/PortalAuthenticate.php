<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('checkinsure.register');
        }

        $user = Auth::user();

        if ($user->registration_channel !== 'third_party' || !$user->third_party_portal_id) {
            return redirect()->route('user.home');
        }

        if ((int) $user->ev !== 1) {
            return redirect()->route('checkinsure.verify');
        }

        return $next($request);
    }
}
