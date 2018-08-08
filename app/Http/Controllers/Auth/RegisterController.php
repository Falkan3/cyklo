<?php

namespace App\Http\Controllers\Auth;

use App\Jobs\SendConfirmationEmail;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'verification_token' => str_random(10)
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));
        dispatch(new SendConfirmationEmail($user));
        return redirect(url(\App::getLocale() . '/login', null, env('HTTPS')))->with('message', __('auth.email_confirmation_sent'));
    }
    /**
     * Handle a registration request for the application.
     *
     * @param $lang
     * @param $token
     * @return \Illuminate\Http\Response
     */
    public function verify($lang = null, $token)
    {
        $user = User::where('verification_token',$token)->first();
        if(empty($user)) {
            return redirect(url(\App::getLocale() . '/login', null, env('HTTPS')))->with('error', __('auth.email_confirmation_error'));
        }
        $user->verified = 1;
        if($user->save()){
            return redirect(url(\App::getLocale() . '/login', null, env('HTTPS')))->with('message', __('auth.email_confirmation_success'));
        }

        return redirect(url(\App::getLocale() . '/login', null, env('HTTPS')))->with('error', __('auth.email_confirmation_error'));
    }
}
