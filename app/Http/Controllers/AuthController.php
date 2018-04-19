<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthControllerPostLogin;
use App\Http\Requests\AuthControllerPostRegister;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * RegisterController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLogin() {
        return view('auth.login');
    }

    /**
     * @param AuthControllerPostLogin $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postLogin(AuthControllerPostLogin $request) {
        if (\Auth::attempt($request->except('_token')))
        {
            return redirect()->route('adminFront');
        }

        return redirect()->back()->withErrors(['message' => 'Something went wrong, try again.']);
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
            return redirect()->route('adminFront');
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
