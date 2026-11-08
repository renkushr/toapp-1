<?php

namespace App\Http\Controllers\ToappAdmin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('toapp_admin.auth.login', [
            'pageTitle' => __('Admin Login'),
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::guard('admin')->attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('username', 'remember'))
                ->withErrors(['username' => 'Username or password is incorrect.']);
        }

        $admin = Auth::guard('admin')->user();

        if ($this->adminIsDisabled($admin)) {
            Auth::guard('admin')->logout();

            return back()
                ->withInput($request->only('username', 'remember'))
                ->withErrors(['username' => 'This admin account is disabled.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('toapp.admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('toapp.admin.login');
    }

    private function adminIsDisabled($admin): bool
    {
        if (!array_key_exists('status', $admin->getAttributes())) {
            return false;
        }

        return $admin->status !== null && (int) $admin->status !== 1;
    }
}
