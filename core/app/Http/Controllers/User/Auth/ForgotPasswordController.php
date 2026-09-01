<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequestForm()
    {
        $pageTitle = "Account Recovery";
        return view('Template::user.auth.passwords.email', compact('pageTitle'));
    }

    public function sendResetCodeEmail(Request $request)
    {
        $request->validate([
            'value' => 'required|string|max:255',
        ]);

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify)->withInput();
        }

        $value = trim($request->string('value')->toString());
        $user = $this->findUser($value);

        if (!$user) {
            $notify[] = ['error', 'No account found for that email or username.'];
            return back()->withNotify($notify)->withInput();
        }

        PasswordReset::where('email', $user->email)->delete();
        $code = verificationCode(6);
        $password = new PasswordReset();
        $password->email = $user->email;
        $password->token = $code;
        $password->created_at = now();
        $password->save();

        $userIpInfo = getIpInfo();
        $userBrowserInfo = osBrowser();

        try {
            notify($user, 'PASS_RESET_CODE', [
                'code' => $code,
                'operating_system' => @$userBrowserInfo['os_platform'],
                'browser' => @$userBrowserInfo['browser'],
                'ip' => @$userIpInfo['ip'],
                'time' => @$userIpInfo['time'],
            ], ['email']);
        } catch (\Throwable $e) {
            Log::error('Password reset email failed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);

            $notify[] = [
                'error',
                'Could not send the recovery email. Please ask an admin to check Email / SMTP settings, then try again.',
            ];
            return back()->withNotify($notify)->withInput();
        }

        if (session()->has('mail_error')) {
            $mailError = session()->pull('mail_error');
            Log::error('Password reset email failed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $mailError,
            ]);

            $notify[] = [
                'error',
                'Could not send the recovery email. Please ask an admin to check Email / SMTP settings at /adto-app/settings/email.',
            ];
            return back()->withNotify($notify)->withInput();
        }

        if (!(int) gs('en')) {
            $notify[] = ['error', 'Email notifications are disabled. Enable them in admin settings before using password recovery.'];
            return back()->withNotify($notify)->withInput();
        }

        session()->put('pass_res_mail', $user->email);
        $notify[] = ['success', 'Recovery code sent. Check your inbox and spam folder.'];
        return to_route('user.password.code.verify')->withNotify($notify);
    }

    protected function findUser(string $value): ?User
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return User::whereRaw('LOWER(email) = ?', [strtolower($value)])->first();
        }

        return User::where('username', $value)->first();
    }

    public function codeVerify(Request $request)
    {
        $pageTitle = 'Verify Email';
        $email = $request->session()->get('pass_res_mail');
        if (!$email) {
            $notify[] = ['error', 'Session expired. Please request a new recovery code.'];
            return to_route('user.password.request')->withNotify($notify);
        }
        return view('Template::user.auth.passwords.code_verify', compact('pageTitle', 'email'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'email' => 'required|email',
        ]);
        $code = str_replace(' ', '', $request->code);

        if (PasswordReset::where('token', $code)->where('email', $request->email)->count() != 1) {
            $notify[] = ['error', 'Verification code doesn\'t match'];
            return back()->withNotify($notify)->withInput();
        }

        session()->put('fpass_email', $request->email);
        session()->put('pass_res_mail', $request->email);

        $notify[] = ['success', 'Code verified. Choose a new password.'];
        return to_route('user.password.reset', $code)->withNotify($notify);
    }
}
