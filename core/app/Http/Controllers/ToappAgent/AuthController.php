<?php

namespace App\Http\Controllers\ToappAgent;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('toapp_agent.auth.login', [
            'pageTitle' => 'Agent Login',
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::guard('agent')->attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('username', 'remember'))
                ->withErrors(['username' => 'Username or password is incorrect.']);
        }

        $agent = Auth::guard('agent')->user();

        if ((int) $agent->status !== 1) {
            Auth::guard('agent')->logout();

            return back()
                ->withInput($request->only('username', 'remember'))
                ->withErrors(['username' => 'This agent account is disabled.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('toapp.agent.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::guard('agent')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('toapp.agent.login');
    }
}
