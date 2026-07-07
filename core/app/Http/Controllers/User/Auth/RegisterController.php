<?php

namespace App\Http\Controllers\User\Auth;


use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\Intended;
use App\Models\AdminNotification;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class RegisterController extends Controller
{

    use RegistersUsers;

    
    protected $redirectTo = '/register2';

    public function __construct()
    {
        $this->middleware('guest');
    }


    public function showRegistrationForm()
    {

        $pageTitle = "Registeration";
        return view('Template::user.auth.register',compact('pageTitle'));
    }


    protected function validator(array $data)
    {

        $passwordValidation = Password::min(6);

        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols();
        }

        $agree = 'nullable';
        if (gs('agree')) {
            $agree = 'required';
        }
        \Session::flash('modal', '#registerModal');

        $validate     = Validator::make($data, [
            'firstname' => 'required',
            'lastname'  => 'required',
            'email'     => 'required|string|email|unique:users',
            'ref_by'    => [
                'required',
                'string',
                'max:40',
                function ($attribute, $value, $fail) {
                    $referralCode = trim($value);
                    $referralExists = User::where('username', $referralCode)
                        ->orWhere('refno', $referralCode)
                        ->exists();

                    if (!$referralExists) {
                        $fail('Please enter a valid referral code.');
                    }
                },
            ],
        ],[
            'firstname.required'=>'The first name field is required',
            'lastname.required'=>'The last name field is required',
            'ref_by.required'=>'A referral code is required to create an account',
        ]);

        return $validate;
    }

    public function register(Request $request)
    {
      
        if (!gs('registration')){
            $notify[]=['error', 'Registration is currently disabled'];
            return back()->withNotify($notify);
        }

        $this->validator($request->all())->validate();

        $request->session()->regenerateToken();

        $referralCode = trim($request->ref_by);
        session()->put('reference', $referralCode);

       

       // event(new Registered($user = $this->create($request->all())));
      
        
        //$this->guard()->login($user);
        $user_register = [
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'ref_by'    => $referralCode,
            'profile_complete' => 0,
            
        ];

        session()->put('user_register', $user_register);
      
        return $this->registered($request)
            ?: redirect($this->redirectPath());
    }

    
    public function registerSubmit(Request $request)
    {

        $user_register  = session()->get('user_register');

        $notify[]=['error', 'Please fill the complete register form'];

        if(!isset($user_register)){
            return to_route('user.register3')->withNotify($notify);
        }
        if($user_register['profile_complete']!=3) {
            return to_route('user.register3')->withNotify($notify);
        }
       
        
        $passwordValidation = Password::min(6);

        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols();
        }

        $request->validate([
            
           'password' => ['required','confirmed',$passwordValidation]
            
        ]);

    
        $password = Hash::make($request->password);

        $user_register['password'] = $password;
        $user_register['profile_complete'] = 1;
        
        event(new Registered($user =   $this->create($user_register)));
        
        session()->forget('user_register');

        $this->guard()->login($user);

        return to_route('user.home');
    }


    public function create(array $data)
    {
        $referBy = session()->get('reference') ?: ($data['ref_by'] ?? null);
        if ($referBy) {
            $referUser = User::where('username', $referBy)
                ->orWhere('refno', $referBy)
                ->first();
        } else {
            $referUser = null;
        }

        //User Create
        $user            = new User();
        $user->email     = strtolower($data['email']);
        $user->firstname = $data['firstname'];
        $user->lastname  = $data['lastname'];

        $user->password  = $data['password'];
        $user->profile_complete = $data['profile_complete'];
        $user->ssn = $data['ssn'];
        $user->zipcode = $data['zipcode'];
        $user->agent_id = $data['agent_id'] ?? null;
        $user->agent_license_no = $data['agent_license_no'] ?? null;
        $user->mobile = $data['mobile'];
        $user->sex = $data['sex'];
        $user->birthday = $data['dob'];
        $user->ref_by    = $referUser ? $referUser->id : 0;
        $user->kv = gs('kv') ? Status::NO : Status::YES;
        $user->refno = Str::random(6);;
        $user->approval_status = 'pending';
        $user->approved_at = null;
        $user->approved_by = null;
        $user->rejected_at = null;
        $user->rejection_reason = null;
        $user->ev = 1;
        $user->sv = 1;
        $user->ts = Status::DISABLE;
        $user->tv = Status::ENABLE;
        $user->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New member waiting for approval';
        $adminNotification->click_url = url('/admin/users/' . $user->id);
        $adminNotification->save();


        //Login Log Create
        $ip        = getRealIP();
        $exist     = UserLogin::where('user_ip', $ip)->first();
        $userLogin = new UserLogin();

        if ($exist) {
            $userLogin->longitude    = $exist->longitude;
            $userLogin->latitude     = $exist->latitude;
            $userLogin->city         = $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country      = $exist->country;
        } else {
            $info                    = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude    = @implode(',', $info['long']);
            $userLogin->latitude     = @implode(',', $info['lat']);
            $userLogin->city         = @implode(',', $info['city']);
            $userLogin->country_code = @implode(',', $info['code']);
            $userLogin->country      = @implode(',', $info['country']);
        }

        $userAgent          = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip = $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os      = @$userAgent['os_platform'];
        $userLogin->save();


        return $user;
    }

    public function checkUser(Request $request){
        $exist['data'] = false;
        $exist['type'] = null;
        if ($request->email) {
            $exist['data'] = User::where('email',$request->email)->exists();
            $exist['type'] = 'email';
            $exist['field'] = 'Email';
        }
        if ($request->mobile) {
            $exist['data'] = User::where('mobile',$request->mobile)->where('dial_code',$request->mobile_code)->exists();
            $exist['type'] = 'mobile';
            $exist['field'] = 'Mobile';
        }
        if ($request->username) {
            $exist['data'] = User::where('username',$request->username)->exists();
            $exist['type'] = 'username';
            $exist['field'] = 'Username';
        }
        return response($exist);
    }

    public function registered()
    {
        return to_route('user.data');
    }

}
