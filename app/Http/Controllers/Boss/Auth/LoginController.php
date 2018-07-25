<?php

namespace App\Http\Controllers\Boss\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Session as Session_m;
use Illuminate\Support\Facades\Session;

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
    protected $redirectTo = '/boss/home';
    protected  $redirectPath = 'google';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:boss')->except('logout');
    }
	/**
	 * Show the application's login form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function showLoginForm()
	{
		return view('boss.auth.login');
	}

	/**
	 * Get the guard to be used during authentication.
	 *
	 * @return \Illuminate\Contracts\Auth\StatefulGuard
	 */
	protected function guard()
	{
		return Auth::guard('boss');
	}

	/**
	 * Send the response after the user was authenticated.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	protected function sendLoginResponse(Request $request)
	{
		$request->session()->regenerate();

		$this->clearLoginAttempts($request);

		return $this->authenticated($request, $this->guard('boss')->user())
			?: redirect()->intended($this->redirectPath());
	}
	/**
	 * The user has been authenticated.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  mixed  $user
	 * @return mixed
	 */
	protected function authenticated(Request $request, $user) :void
	{
		//TODO::need modify via Event Listener or smth else
		/*$request->session()->put('is_boss',1);
		$request->session()->save();
		if($request->session()->getId()!=='' && $request->session()->has('is_boss'))
		Session_m::where('id',Session::getId())->update(['is_boss'=>1,'user_id'=>0]);
		*/
	}
}
