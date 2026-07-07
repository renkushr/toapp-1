<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToappAdminAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('toapp.admin.login');
        }

        $admin = Auth::guard('admin')->user();

        if ($admin->status !== null && (int) $admin->status !== 1) {
            Auth::guard('admin')->logout();

            return redirect()->route('toapp.admin.login')->withErrors(['username' => 'This admin account is disabled.']);
        }

        return $next($request);
    }
}
