<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthControllerPostlogin;
use App\Http\Requests\AuthControllerPostRegister;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'userProfile';

    /**
     * RegisterController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function getLogin() {
        return view('auth.login');
    }

    public function postLogin(AuthControllerPostlogin $request) {
        if (\Auth::attempt([
            'email' => $request->get('email'),
            'password' => $request->get('password')]))
        {
            return redirect()->route('userProfile');
        }

        return redirect()->back();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getRegister() {
        return view('auth.register');
    }

    /**
     * @param AuthControllerPostRegister $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postRegister(AuthControllerPostRegister $request) {
        User::create([
            'username' => $request->get('username'),
            'email' => $request->get('email'),
            'password' => \Hash::make($request->get('password')),
        ]);
        if (\Auth::attempt([
            'email' => $request->get('email'), 'password' => $request->get('password')])) {
            return redirect()->route('userProfile');
        }

        return abort(520); // @TODO Rewrite this to something less 520ish
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request) {
        \Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('home');
    }
}
