<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\ActivityLogJob;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if (auth()->attempt($request->except('_token'))){
            //Logging
            $activity = [
                'name'=>'Authentication',
                'description'=>'login',
                'user_id'=>auth()->id(),
                'logged_at'=>Carbon::now()
            ];
            $this->dispatch(new ActivityLogJob($activity));

            return redirect('/home');
        }else{
            return redirect()->back()->withErrors(['email'=>'invalid email or password','password'=>'invalid email or password']);
        }

    }

    public function logout(Request $request)
    {
        //Logging
        $activity = [
            'name'=>'Authentication',
            'description'=>'logout',
            'user_id'=>auth()->id(),
            'logged_at'=>Carbon::now()
        ];
        $this->dispatch(new ActivityLogJob($activity));

        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }
}
