<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthControllerPostLogin;
use App\Http\Requests\AuthControllerPostRegister;
use App\Models\Invite;
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
            return redirect()->route('questionsAll');
        }

        return redirect()->back()->withErrors(['message' => 'Something went wrong, try again.', 'alert_type' => 'danger']);
    }

    /**
     * @param Request $request
     * @param Invite  $invite
     *
     * @return $this
     */
    public function getRegister(Request $request, Invite $invite) {
        if(!$request->query->has('token')){
            abort(404);
        }

        $token = $invite->where(['token' => $request->query->get('token'), 'is_valid' => true])->first();
        if(null === $token) {
            abort(404);
        }

        return view('auth.register')->with(['token' => $token]);
    }

    /**
     * @param AuthControllerPostRegister $request
     * @param Invite                     $invite
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postRegister(AuthControllerPostRegister $request, Invite $invite) {
        if(!$request->request->has('token')){
            abort(404);
        }

        $token = $invite->where(['token' => $request->request->get('token'), 'is_valid' => true])->first();

        if(null === $token) {
            abort(404);
        }

        User::create([
            'username' => $request->get('username'),
            'email' => $token->to,
            'password' => \Hash::make($request->get('password')),
        ]);
        if (\Auth::attempt([
            'email' => $token->to, 'password' => $request->get('password')])) {
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
        return redirect()->route('getLogin');
    }
}
