<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalGuest
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->registration_channel === 'third_party') {
            $user = Auth::user();

            if ((int) $user->ev !== 1) {
                return redirect()->route('checkinsure.verify');
            }

            if (!$user->profile_complete) {
                return redirect()->route('checkinsure.profile');
            }

            return redirect()->route('checkinsure.profile');
        }

        return $next($request);
    }
}
