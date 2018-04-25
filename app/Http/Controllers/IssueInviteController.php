<?php

namespace App\Http\Controllers;

use App\Http\Requests\IssueInvitePostRequest;
use App\Mapping\RolesAndPermissions;
use App\Models\Invite;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Class IssueInviteController
 *
 * @package App\Http\Controllers
 */
class IssueInviteController extends Controller
{

  /**
   * @param \App\User                        $user
   * @param \App\Mapping\RolesAndPermissions $roles_and_permissions
   *
   * @return $this
   */
    public function show(User $user, RolesAndPermissions $roles_and_permissions)
    {
        return view('admin.invite.issue')->with([
          'user' => $user->find(\Auth::user()->id),
          'roles' => $roles_and_permissions->getAllRoles(),
        ]);
    }

    /**
     * @param IssueInvitePostRequest $request
     * @param Invite                 $invite
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function issue(IssueInvitePostRequest $request, Invite $invite)
    {
        $token = Str::random(50);

        if(!$request->has('roles')) {
          return redirect()->back()->with(['message' => 'At least one role must be selected', 'alert_type' => 'danger']);
        }

        $roles = $request->get('roles');

        $current_invite = $invite->where(['to' => $request->get('to'), 'is_valid' => true])->first();

        if (null !== $current_invite && $current_invite->count() >= 1) {
            $current_invite->is_valid = false;
            $current_invite->save();
        }

        $invite = $invite->create(
            [
                'from'     => \Auth::user()->email,
                'token'    => $token,
                'to'       => $request->get('to'),
                'is_valid' => true,
                'validity' => $request->get('validity'),
            ]
        );

        foreach($roles as $role) {
          $invite->roles()->create([
              'invite_id' => $invite->id,
              'role_name' => $role,
          ]);
        }

        Mail::send(
            'mail.issue', // view
            [
                // data passed to the view
                'body' => $request->get('body'),
                'link' => route('getRegister', ['token' => $token]),
            ],
            function ($m) use ($request){
                $m->to($request->get('to'))->subject($request->get('subject'));
            }
        );

        return redirect()->back()->with(['message' => 'Email sent!', 'alert_type' => 'success']);
    }

    /**
     * @param Request $request
     * @param Invite  $invite
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function invalidToken(Request $request, Invite $invite) {
        if(!$request->has('token')){
            return view('responses.invalid_invite');
        }

        $valid = $invite->where('token', $request->get('token'))->first();

        if(null === $valid){
            return view('responses.invalid_invite');
        }

        return view('responses.invalid_invite');
    }

    public function revoke(Request $request, Invite $invite_model) {
        if(!$request->has('invite_id')) {
            return new JsonResponse([
               'success' => false,
               'message' => 'Parameter missing from job',
               'status'  => 500,
            ]);
        }

        try{
            $invite = $invite_model->where(['id' => $request->get('invite_id')])->first();

            if(0 == (int)$invite->is_valid) {
                return new JsonResponse([
                    'success' => true,
                    'message' => 'Invite already revoked',
                    'status'  => 200,
                ]);
            }

            $invite->is_valid = false;
            $invite->save();
        }catch (\Exception $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Unable to revoke invite',
                'status'  => 500,
            ]);
        }

        return new JsonResponse([
            'success' => true,
            'message' => 'Invite revoked successfully',
            'status'  => 200,
        ]);
    }

    public function getUserInvites(Invite $invite_model) {
        $invites = $invite_model->orderBy('created_at', 'DESC')->get();

        return view('admin.invite.users.invites')->with(['invites' => $invites]);
    }
}
