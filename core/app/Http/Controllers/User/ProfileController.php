<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\WithdrawMethod;
use App\Services\AgentAssignmentService;
use App\Support\UsZip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function profile()
    {
        $pageTitle = "Profile Setting";
        $user = auth()->user();
        return view('Template::user.profile_setting', compact('pageTitle','user'));
    }

    public function submitProfile(Request $request, AgentAssignmentService $agentAssignment)
    {
        $request->validate([
            'firstname' => 'required|string|max:40',
            'lastname'  => 'required|string|max:40',
            'mobile'    => 'required|string|max:40',
            'zipcode'   => ['required', 'regex:/^\d{5}(-\d{4})?$/'],
        ]);

        $user = auth()->user();
        $normalizedZip = UsZip::normalize($request->zipcode);

        if ($user->agent_id && $user->agent_license_no) {
            $agentAssignment->resolve($user->agent_license_no, $normalizedZip);
        }

        $user->firstname = $request->firstname;
        $user->lastname  = $request->lastname;
        $user->mobile = $request->mobile;
        $user->zipcode = $normalizedZip;

       
       

        $user->save();
        $notify[] = ['success', 'Profile updated successfully'];
        return back()->withNotify($notify);
    }


    public function bank()
    {
        $pageTitle = 'Withdraw Methods';
        $user = auth()->user();
        $paypalMethod = WithdrawMethod::active()
            ->whereRaw('LOWER(name) LIKE ?', ['%paypal%'])
            ->first();
        $bankMethod = WithdrawMethod::active()
            ->whereRaw('LOWER(name) LIKE ?', ['%bank%'])
            ->first();

        return view('Template::user.bank_setting', compact('pageTitle', 'user', 'paypalMethod', 'bankMethod'));
    }

    public function submitBank(Request $request)
    {
        $request->validate([
            'payout_method' => 'required|in:paypal,bank',
        ]);

        if ($request->payout_method === 'paypal') {
            $request->validate([
                'payout_paypal_email' => 'required|email|max:120',
            ]);
        } else {
            $request->validate([
                'payout_bank_name' => 'required|string|max:120',
                'payout_account_name' => 'required|string|max:120',
                'payout_account_number' => 'required|string|max:60',
                'payout_routing_number' => 'required|string|max:40',
            ]);
        }

        $user = auth()->user();
        $user->payout_method = $request->payout_method;

        if ($request->payout_method === 'paypal') {
            $user->payout_paypal_email = strtolower(trim($request->payout_paypal_email));
            $user->payout_bank_name = null;
            $user->payout_account_name = null;
            $user->payout_account_number = null;
            $user->payout_routing_number = null;
        } else {
            $user->payout_paypal_email = null;
            $user->payout_bank_name = trim($request->payout_bank_name);
            $user->payout_account_name = trim($request->payout_account_name);
            $user->payout_account_number = trim($request->payout_account_number);
            $user->payout_routing_number = trim($request->payout_routing_number);
        }

        $user->save();

        $notify[] = ['success', 'Payout details saved successfully'];
        return back()->withNotify($notify);
    }


    public function changePassword()
    {
        $pageTitle = 'Change Password';
        return view('Template::user.password', compact('pageTitle'));
    }

    public function submitPassword(Request $request)
    {

        $passwordValidation = Password::min(6);
        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols();
        }

        $request->validate([
            'current_password' => 'required',
            'password' => ['required','confirmed',$passwordValidation]
        ]);

        $user = auth()->user();
        if (Hash::check($request->current_password, $user->password)) {
            $password = Hash::make($request->password);
            $user->password = $password;
            $user->save();
            $notify[] = ['success', 'Password changed successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'The password doesn\'t match!'];
            return back()->withNotify($notify);
        }
    }
}
