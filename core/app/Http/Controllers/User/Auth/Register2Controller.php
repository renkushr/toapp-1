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

class Register2Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }



    public function showRegistrationForm()
    {

        $pageTitle = "Register";
        return view('Template::user.auth.register2',compact('pageTitle'));
    }


    protected function validator(array $data)
    {

        

        $validate     = Validator::make($data, [
           
        
            'zip'     => 'required',
            'ssn'  => ['required'],
            'mobile'   => 'required'
           
        ],[
            'sex.required'=>'The first name field is required',
            'zip.required'=>'The ZIP code field is required'
        ]);

        return $validate;
    }

    public function register2(Request $request)
    {

        $user = auth()->user();
        $user->sex = 1;
        $user->ssn = $request->ssn;
        $user->mobile = $request->mobile;
        $user->save();

        return to_route('user.register3');
    }



   

}