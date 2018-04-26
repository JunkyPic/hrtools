<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserControllerPostChangePassword;

/**
 * Class UserController
 *
 * @package App\Http\Controllers
 */
class UserController extends Controller
{

  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
    public function profile() {
        return view('user.profile');
    }

  /**
   * @param \App\Http\Requests\UserControllerPostChangePassword $request
   *
   * @return \Illuminate\Http\RedirectResponse
   */
    public function changePassword(UserControllerPostChangePassword $request) {
        $current_password = $request->get('current_password');
        $new_password = $request->get('password');

        if(!\Hash::check($current_password, \Auth::user()->password)) {
          return redirect()->back()->with(['message' => 'Current password does not match the stored password', 'alert_type' => 'danger']);
        }
      //Change Password
      $user = \Auth::user();
      $user->password = \Hash::make($new_password);
      $user->save();

      return redirect()->back()->with(['message' => 'Password changed successfully', 'alert_type' => 'success']);
    }
}
