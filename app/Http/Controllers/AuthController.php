<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthControllerPostLogin;
use App\Http\Requests\AuthControllerPostRegister;
use App\Mapping\RolesAndPermissions;
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
    public function getLogin()
    {
        return view('auth.login');
    }

    /**
     * @param AuthControllerPostLogin $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postLogin(AuthControllerPostLogin $request)
    {
        if (\Auth::attempt($request->except('_token'))) {
            return redirect()->route('questionsAll');
        }

        return redirect()->back()->withErrors(
            ['message' => 'Something went wrong, try again.', 'alert_type' => 'danger']
        );
    }

    /**
     * @param Request $request
     * @param Invite  $invite
     *
     * @return $this
     */
    public function getRegister(Request $request, Invite $invite)
    {
        if ( ! $request->query->has('token')) {
            return view('responses.invalid_invite');
        }

        $token = $invite->where(['token' => $request->query->get('token'), 'is_valid' => true])->first();

        if (null === $token) {
            return view('responses.invalid_invite');
        }

        return view('auth.register')->with(['token' => $token]);
    }

  /**
   * @param \App\Http\Requests\AuthControllerPostRegister $request
   * @param \App\Models\Invite                            $invite
   * @param \App\Mapping\RolesAndPermissions              $roles_and_permissions
   * @param \App\User                                     $user
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
   */
    public function postRegister(AuthControllerPostRegister $request, Invite $invite, RolesAndPermissions $roles_and_permissions, User $user)
    {
        if ( ! $request->request->has('token')) {
            return view('responses.invalid_invite');
        }

        $model = $invite->with('roles')->where(['token' => $request->request->get('token'), 'is_valid' => true])->first();

        if (null === $model) {
            abort(404);
        }

        $model->is_valid = false;
        $model->save();

        $user->create(
            [
                'username' => $request->get('username'),
                'email'    => $model->to,
                'password' => \Hash::make($request->get('password')),
            ]
        );


        if (\Auth::attempt(
            [
                'email' => $model->to,
                'password' => $request->get('password'),
            ]
        )) {
          $user = \Auth::user();
          $roles = $roles_and_permissions->getAllRoles();

          foreach($model->roles as $role) {
            if(!in_array($role->role_name, $roles)) {
              continue;
            }

            if(!$user->hasRole($role->role_name)) {
              $user->assignRole($role->role_name);
            }
          }

            return redirect()->route('userProfile');
        }

        return abort(520); // @TODO Rewrite this to something less 520ish
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        \Auth::logout();
        $request->session()->invalidate();

        return redirect()->route('getLogin');
    }
}
