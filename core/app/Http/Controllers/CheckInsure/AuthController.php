<?php

namespace App\Http\Controllers\CheckInsure;

use App\Constants\Status;
use App\Models\InsuranceApplication;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends PortalController
{
    public function showLogin()
    {
        return view('check_insure.login', [
            'pageTitle' => 'Sign in',
            'portal' => $this->portal(),
        ]);
    }

    public function login(Request $request)
    {
        $portal = $this->portal();

        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $email = strtolower($data['email']);
        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
        }

        if ((int) $user->status !== Status::USER_ACTIVE) {
            return back()->withErrors(['email' => 'This account is not active.'])->withInput();
        }

        // Existing To-App members can sign into Check Insure with the same email/password.
        if (
            $user->registration_channel !== 'third_party'
            || (int) $user->third_party_portal_id !== (int) $portal->id
        ) {
            $user->registration_channel = 'third_party';
            $user->third_party_portal_id = $portal->id;
            $user->save();
        }

        if (!$this->applicationFor($user)) {
            InsuranceApplication::create([
                'third_party_portal_id' => $portal->id,
                'user_id' => $user->id,
                'reference' => InsuranceApplication::generateReference(),
                'status' => (int) $user->ev !== Status::VERIFIED
                    ? InsuranceApplication::STATUS_OTP_PENDING
                    : InsuranceApplication::STATUS_PROFILE_PENDING,
            ]);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        $this->restorePortalSession($user);

        return $this->intendedPortalRedirect($user)
            ->with('status', 'Welcome back.');
    }

    public function showRegister()
    {
        return view('check_insure.register', [
            'pageTitle' => 'Find your insurance',
            'portal' => $this->portal(),
        ]);
    }

    public function register(Request $request)
    {
        $portal = $this->portal();

        $passwordValidation = Password::min(6);
        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols();
        }

        $data = $request->validate([
            'firstname' => ['required', 'string', 'max:40'],
            'lastname' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:40', 'unique:users,email'],
            'password' => ['required', 'confirmed', $passwordValidation],
        ]);

        $user = new User();
        $user->firstname = $data['firstname'];
        $user->lastname = $data['lastname'];
        $user->email = strtolower($data['email']);
        $user->password = Hash::make($data['password']);
        $user->username = $this->uniqueUsername($data['email']);
        $user->ref_by = 0;
        $user->refno = Str::random(6);
        $user->registration_channel = 'third_party';
        $user->third_party_portal_id = $portal->id;
        $user->ev = Status::UNVERIFIED;
        $user->sv = Status::VERIFIED;
        $user->kv = Status::YES;
        $user->approval_status = 'approved';
        $user->profile_complete = 0;
        $user->ver_code = verificationCode(6);
        $user->ver_code_send_at = now();
        $user->status = Status::USER_ACTIVE;
        $user->save();

        InsuranceApplication::create([
            'third_party_portal_id' => $portal->id,
            'user_id' => $user->id,
            'reference' => InsuranceApplication::generateReference(),
            'status' => InsuranceApplication::STATUS_OTP_PENDING,
        ]);

        notify($user, 'EVER_CODE', [
            'code' => $user->ver_code,
        ], ['email']);

        if (session()->has('mail_error')) {
            $mailError = session()->pull('mail_error');
            Log::error('Check Insure OTP email failed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $mailError,
            ]);

            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('checkinsure.verify')
                ->withErrors(['code' => 'Could not send the verification email. Please ask an admin to check Email settings (SendGrid), then tap Resend.']);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('checkinsure.verify')
            ->with('status', 'We sent a verification code to your email.');
    }

    public function showVerify()
    {
        $user = Auth::user();
        abort_unless($user && $user->registration_channel === 'third_party', 403);

        return view('check_insure.verify', [
            'pageTitle' => 'Verify Email',
            'portal' => $this->portal(),
            'user' => $user,
        ]);
    }

    public function verify(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->registration_channel === 'third_party', 403);

        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        if ($user->ver_code !== $request->code) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        $user->ev = Status::VERIFIED;
        $user->ver_code = null;
        $user->ver_code_send_at = null;
        $user->save();

        $this->applicationFor($user)?->update([
            'status' => InsuranceApplication::STATUS_PROFILE_PENDING,
        ]);

        return redirect()->route('checkinsure.profile')
            ->with('status', 'Email verified. Please complete your profile.');
    }

    public function resendOtp()
    {
        $user = Auth::user();
        abort_unless($user && $user->registration_channel === 'third_party', 403);

        if ($user->ver_code_send_at && $user->ver_code_send_at->addMinutes(2)->isFuture()) {
            return back()->withErrors(['code' => 'Please wait before requesting another code.']);
        }

        $user->ver_code = verificationCode(6);
        $user->ver_code_send_at = now();
        $user->save();

        notify($user, 'EVER_CODE', ['code' => $user->ver_code], ['email']);

        if (session()->has('mail_error')) {
            $mailError = session()->pull('mail_error');
            Log::error('Check Insure OTP email failed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $mailError,
            ]);

            return back()->withErrors([
                'code' => 'Could not send the verification email. Please ask an admin to check Email settings (SendGrid).',
            ]);
        }

        return back()->with('status', 'A new verification code has been sent.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('checkinsure.register');
    }

    protected function intendedPortalRedirect(User $user)
    {
        if ((int) $user->ev !== Status::VERIFIED) {
            return redirect()->route('checkinsure.verify');
        }

        $application = $this->applicationFor($user);

        if ($application) {
            if (in_array($application->status, [
                InsuranceApplication::STATUS_CHATTING,
                InsuranceApplication::STATUS_PAYMENT_PENDING,
            ], true)) {
                return redirect()->route('checkinsure.chat', $application);
            }

            if ($application->status === InsuranceApplication::STATUS_PLAN_SELECTED) {
                return redirect()->route('checkinsure.terms', $application);
            }

            if ($application->status === InsuranceApplication::STATUS_TERMS_ACCEPTED) {
                return redirect()->route('checkinsure.waiting_agent', $application);
            }

            if ($application->status === InsuranceApplication::STATUS_QUOTED && session('checkinsure_criteria')) {
                return redirect()->route('checkinsure.results');
            }
        }

        if (!$user->profile_complete) {
            return redirect()->route('checkinsure.profile');
        }

        return redirect()->route('checkinsure.profile');
    }

    protected function restorePortalSession(User $user): void
    {
        if (session('checkinsure_criteria')) {
            return;
        }

        $application = $this->applicationFor($user);

        if (!$application || !$user->profile_complete || !$user->birthday) {
            return;
        }

        $birthday = $user->birthday instanceof Carbon
            ? $user->birthday->format('Y-m-d')
            : (string) $user->birthday;

        session([
            'checkinsure_profile_step' => [
                'sex' => (int) $user->sex,
                'birthday' => $birthday,
                'zipcode' => $user->zipcode,
            ],
            'checkinsure_criteria' => [
                'sex' => (int) $user->sex,
                'birthday' => $birthday,
                'zipcode' => $user->zipcode,
                'chronic_disease' => (bool) $user->chronic_disease,
                'smoking' => (int) $user->nicotin,
                'health' => (int) $user->health,
                'pay_type' => $application->pay_type ?: 'year',
                'product_category' => session('checkinsure_criteria.product_category'),
                'price_range' => (int) ($application->price_range ?: 1),
            ],
        ]);
    }

    protected function applicationFor(User $user): ?InsuranceApplication
    {
        return InsuranceApplication::where('user_id', $user->id)
            ->where('third_party_portal_id', $this->portal()->id)
            ->latest('id')
            ->first();
    }

    protected function uniqueUsername(string $email): string
    {
        $base = Str::slug(Str::before($email, '@'), '');
        $base = $base ?: 'member';
        $username = $base;
        $i = 1;

        while (User::where('username', $username)->exists()) {
            $username = $base . $i;
            $i++;
        }

        return $username;
    }
}
