<?php

namespace App\Http\Controllers;

use App\Http\Requests\CandidateInviteControllerPostCreateInvite;
use App\Models\Candidate;
use App\Models\Test;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Class CandidateInviteController
 *
 * @package App\Http\Controllers
 */
class CandidateInviteController extends Controller
{
    /**
     * @param User $user_model
     * @param Test $test_model
     *
     * @return $this
     */
    public function getCreateInvite(User $user_model, Test $test_model)
    {
        return view('admin.invite.candidate.issue_invite')->with([
            'user' => $user_model->find(\Auth::id())->first(),
            'tests' => $test_model->get(),
        ]);
    }

    /**
     * @param CandidateInviteControllerPostCreateInvite $request
     * @param Candidate                                 $invite_model
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateInvite(CandidateInviteControllerPostCreateInvite $request, Candidate $invite_model)
    {
        $email_token = Str::random(100);
        $test_token = Str::random(100);

        $current_invite = $invite_model->where([
            'to_email' => $request->get('to'),
            'is_email_token_valid' => true,
            'is_invite_token_valid' => true,
        ])->first();

        if (null !== $current_invite && $current_invite->count() >= 1) {
            $current_invite->is_email_token_valid = false;
            $current_invite->is_invite_token_valid = false;
            $current_invite->save();
        }

        $invite_model->create(
            [
                'email_token'           => $email_token,
                'test_id'               => $request->get('test_id'),
                'test_token'            => $test_token,
                'to_email'              => $request->get('to'),
                'to_fullname'           => $request->get('to_fullname'),
                'from'                  => \Auth::user()->email,
                'test_validity'         => $request->get('test_validity'),
                'invite_validity'       => $request->get('invite_validity'),
                'is_email_token_valid'  => true,
                'is_invite_token_valid' => true,
            ]
        );

        Mail::send(
            'mail.issue', // view
            [
                // data passed to the view
                'body' => $request->get('message'),
                'link' => route('takeTest', ['t' => $test_token]),
            ],
            function ($m) use ($request){
                $m->to($request->get('to'))->subject($request->get('subject'));
            }
        );

        return redirect()->back()->with(['message' => 'Email sent!', 'alert_type' => 'success']);
    }
}
