<?php

namespace App\Http\Controllers;

use App\Http\Requests\CandidateInviteControllerPostCreateInvite;
use App\Models\Candidate;
use App\Models\CandidateInviteInform;
use App\Models\CandidateTest;
use App\Models\Test;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        return view('admin.invite.candidate.issue_invite')->with(
            [
                'user'  => $user_model->find(\Auth::id())->first(),
                'tests' => $test_model->get(),
            ]
        );
    }

  /**
   * @param \App\Http\Requests\CandidateInviteControllerPostCreateInvite $request
   * @param \App\Models\Candidate                                        $candidate_model
   * @param \App\Models\CandidateInviteInform                            $candidate_invite_inform
   *
   * @return \Illuminate\Http\RedirectResponse
   */
    public function postCreateInvite(CandidateInviteControllerPostCreateInvite $request, Candidate $candidate_model, CandidateInviteInform $candidate_invite_inform)
    {
            $test_token = Str::random(100);
            $email = $request->get('to');
            $fullname = $request->get('fullname');

            if($request->has('emails')) {
              $inform = explode(',', $request->get('emails'));
            }

            // check if that email is already registered to a candidate
            $candidate = $candidate_model->where('to', $email)->first();
            if (null !== $candidate) {
                // Email already exists
                // Simply create a new entry in the CandidateTest table and invalidate the previous one
                $candidate_invite = $candidate->candidateTest()->get();

                // Unfortunately Laravel doesn't offer mass assignment via relationship
                // So they have to be done one by one
                foreach ($candidate_invite as $item) {
                    $item->is_valid = false;
                    $item->save();
                }
            }else {
                // Create a new CandidateTest instance
                $candidate = $candidate_model->create(
                    [
                        'to'       => $email,
                        'fullname' => $fullname,
                        'from'     => \Auth::user()->email,
                        'test_id'  => $request->get('test_id'),
                    ]
                );
            }

            // Create a new instance of the newly updated candidate test
            $test = $candidate->candidateTest()->create(
                [
                    'token'        => $test_token,
                    'is_valid'     => true,
                    'validity'     => $request->get('test_validity'),
                    'started_at'   => null,
                    'finished_at'  => null,
                    'test_id'      => $request->get('test_id'),
                    'candidate_id' => $candidate->id,
                ]
            );

            if(isset($inform) && !empty($inform)) {
              foreach($inform as $email) {
                $candidate_invite_inform->create(
                  [
                    'email'             => $email,
                    'candidate_test_id' => $test->id,
                  ]
                );
              }
            }

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

    public function all(Candidate $candidate) {
      $invites = $candidate->with('candidateTest')->get();

      return view('admin.invite.candidate.invites')->with(['invites' => $invites]);
    }

    public function invalidateInvite(Request $request, CandidateTest $candidate_test) {
      if(!$request->has('test_id')) {
        return new JsonResponse([
          'message' => 'Parameter not found. Unable to execute action, please contact a system administrator.',
        ]);
      }

      $test_id = $request->get('test_id');

      $candidate_test = $candidate_test->where(['id' => $test_id])->first();


      if(null === $candidate_test) {
        return new JsonResponse([
          'message' => 'Unable to find test with test_id' . $test_id . '. Contact a system administrator.',
        ]);
      }

      $candidate_test->is_valid = false;
      if($candidate_test->save()) {
        return new JsonResponse([
          'success' => true,
          'message' => 'Invite revoked successfully',
        ]);
      }

      return new JsonResponse([
        'message' => 'Unable to revoke invite at this time',
      ]);
    }
}
