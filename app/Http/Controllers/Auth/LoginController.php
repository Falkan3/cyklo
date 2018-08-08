<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendConfirmationEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    use AuthenticatesUsers {
        logout as performLogout;
    }

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
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectTo()
    {
        // Logic that determines where to send the user
        if (Auth::user()->isAdmin()) {
            return '/admin';
        }

        return '/home';
    }

    protected function authenticated($request, $user)
    {
        if($user->verified==0) {
            dispatch(new SendConfirmationEmail($user));
            auth()->logout();
            return redirect(url(\Lang::getLocale() . '/login', null, env('HTTPS')))->withInput()->with('error', __('auth.account_must_be_validated'));
        }
        if($user->isAdmin()){
            return redirect()->intended(\Lang::getLocale() . '/admin');
        }

        return redirect()->intended(\Lang::getLocale() . '/home');
    }

    public function logout(\Illuminate\Http\Request $request)
    {
        $this->performLogout($request);
        return redirect()->back();
        //return redirect(\Lang::getLocale());
    }
}
