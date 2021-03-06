<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestControllerPostCreateDefaultTestMessage;
use App\Http\Requests\TestControllerPostCreateTest;
use App\Http\Requests\TestControllerPostEditTest;
use App\Http\Requests\TestControllerPostSubmitReview;
use App\Http\Requests\TestControllerPostUpdateReview;
use App\Mapping\RolesAndPermissions;
use App\Models\Candidate;
use App\Models\CandidateTest;
use App\Models\Review;
use App\Models\Test;
use App\Models\TestDefaultMessage;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Class TestController
 *
 * @package App\Http\Controllers
 */
class TestController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate() {
        return view('admin.test.create');
    }

    /**
     * @param TestControllerPostCreateTest $request
     * @param Test                         $test_model
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreate(TestControllerPostCreateTest $request, Test $test_model) {

        $test_model->create([
            'name' => $request->get('name'),
            'instructions' => $request->get('instructions'),
            'information' => $request->get('information'),
            'end_test_message' => $request->get('end_test_message'),
        ]);

        return redirect()->back()->with(['message' => 'Test created successfully', 'alert_type' => 'success']);
    }

    /**
     * @param Test $test_model
     *
     * @return $this
     */
    public function all(Test $test_model) {
        return view('admin.test.tests')
            ->with([
                'tests' => $test_model->with(['chapters.questions'])->orderBy('created_at', 'DESC')->paginate(1)
            ]);
    }

    /**
     * @param      $test_id
     * @param Test $test_model
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function getEdit($test_id, Test $test_model) {
        $test = $test_model->find($test_id);

        return view('admin.test.edit')->with(['test' => $test]);
    }

    /**
     * @param                            $test_id
     * @param TestControllerPostEditTest $request
     * @param Test                       $test_model
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit($test_id, TestControllerPostEditTest $request, Test $test_model) {
        $test = $test_model->where(['id' => $test_id])->first();

        $test->update([
            'name' => $request->get('name'),
            'information' => $request->get('information'),
        ]);

        return redirect()->back()->with(['message' => 'Test updated successfully', 'alert_type' => 'success']);
    }

  /**
   * @param                                  $test_id
   * @param \App\Mapping\RolesAndPermissions $roles_and_permissions
   * @param \App\Models\Test                 $test_model
   *
   * @return \Illuminate\Http\RedirectResponse
   * @throws \Illuminate\Auth\Access\AuthorizationException
   */
    public function delete($test_id, RolesAndPermissions $roles_and_permissions, Test $test_model) {
      if(!$roles_and_permissions->hasPermissionTo(RolesAndPermissions::PERMISSION_DELETE_CHAPTER)) {
        throw new AuthorizationException();
      }
        $test = $test_model->where(['id' => $test_id])->first();

        $test->delete();

        return redirect()->route('testAll')->with(['message' => 'Test deleted successfully', 'alert_type' => 'success']);
    }

    /**
     * @param CandidateTest $candidate_test
     *
     * @return $this
     */
    public function taken(CandidateTest $candidate_test) {
        $candidate_answers = $candidate_test
            ->whereHas('answers')
            ->with('answers', 'candidate')
            ->paginate(20);

        return view('admin.test.candidate.list')->with([
            'candidate_answers' => $candidate_answers
        ]);
    }

    /**
     * @param               $candidate_id
     * @param               $test_id
     * @param CandidateTest $candidate_test_model
     * @param Review        $review
     * @param Candidate     $candidate_model
     *
     * @return $this
     */
    public function review($candidate_id, $test_id, CandidateTest $candidate_test_model, Review $review, Candidate $candidate_model) {
        $reviews = $review->where([
            'user_id' => \Auth::user()->id,
            'candidate_test_id' => $test_id,
            'candidate_id' => $candidate_id
        ])
            ->with('answers.images')
            ->get();

        $candidate = $candidate_model->where(['id' => $candidate_id])->first();

        if($reviews->count() >= 1) {
            return view('admin.test.candidate.user_review')->with([
                'candidate' => $candidate,
                'reviews' => $reviews,
                'candidate_id' => $candidate_id,
                'test_id' => $test_id,
                'user_id' => \Auth::user()->id,
                'image_display_path' => \Config::get('image.answer_image_display')
            ]);
        }

        $candidate = $candidate_test_model
            ->with('answers.images', 'candidate')
            ->whereHas('candidate', function ($query) use ($candidate_id) {
                $query->where('candidate_id', '=', $candidate_id);
            })
            ->whereHas('answers', function ($query) use ($test_id) {
                $query->where('candidate_test_id', '=', $test_id);
            })
            ->first();

        return view('admin.test.candidate.review')->with([
            'candidate' => $candidate,
            'candidate_id' => $candidate_id,
            'test_id' => $test_id,
            'user_id' => \Auth::user()->id,
            'image_display_path' => \Config::get('image.answer_image_display')
        ]);
    }

    /**
     * @param TestControllerPostUpdateReview $request
     * @param Review                         $review
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reviewUpdate(TestControllerPostUpdateReview $request, Review $review) {
        $candidate_id = $request->get('candidate_id');
        $candidate_test_id = $request->get('candidate_test_id');
        $is_correct = $request->get('is_correct');
        $notes = $request->get('notes');

        $reviews = $review->where([
            'user_id' => \Auth::user()->id,
            'candidate_test_id' => $candidate_test_id,
            'candidate_id' => $candidate_id
        ])
            ->with('answers.images')
            ->get();

        // Gotta love not being able to mass assign with relation
        foreach ($reviews as $review) {

            $review->is_correct = $is_correct[$review->id];
            $review->notes = $notes[$review->id];
            $review->save();
        }

        return redirect()->back()->with(['message' => 'Review updated successfully', 'alert_type' => 'success']);
    }

    /**
     * @param TestControllerPostSubmitReview $request
     * @param Review                         $review
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reviewSubmit(TestControllerPostSubmitReview $request, Review $review) {
        $candidate_id = $request->get('candidate_id');
        $candidate_test_id = $request->get('candidate_test_id');
        $is_correct = $request->get('is_correct');
        $notes = $request->get('notes');
        $user_id = \Auth::user()->id;

        // Gotta love not being able to mass assign with relation
        foreach ($is_correct as $key => $item) {
            switch ($item) {
                case Review::CORRECT:
                    $status = Review::CORRECT;
                    break;
                case Review::PARTIALLY_CORRECT:
                    $status = Review::PARTIALLY_CORRECT;
                    break;
                case Review::INCORRECT:
                    $status = Review::INCORRECT;
                    break;
                default:
                    $status = Review::REQUIRES_ADDITIONAL_REVIEW;
                    break;
            }

            $review = $review->create([
                'is_correct' => $status,
                'notes' => $notes[$key],
                'answer_id' => $key,
                'user_id' => $user_id,
                'candidate_id' => $candidate_id,
                'candidate_test_id' => $candidate_test_id,
            ]);
        }

        return redirect()->back()->with(['message' => 'Review submitted successfully', 'alert_type' => 'success']);
    }

    /**
     * @param TestDefaultMessage $message_model
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSetTestsDefaultMessage(TestDefaultMessage $message_model) {
        return view('admin.test.default_message.create')->with(['default_message' => $message_model->first()]);
    }

    /**
     * @param TestControllerPostCreateDefaultTestMessage $request
     * @param TestDefaultMessage                         $message_model
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSetTestsDefaultMessage(TestControllerPostCreateDefaultTestMessage $request, TestDefaultMessage $message_model) {
        $default_message = $request->get('default_message');

        $message_model_instance = $message_model->first();

        if(null === $message_model_instance) {
            $message_model->create($request->except('_token'));
            return redirect()->back()->with(['message' => 'Default message saved successfully', 'alert_type' => 'success']);
        }

        $message_model_instance->default_message = $default_message;
        if($message_model_instance->save()) {
            return redirect()->back()->with(['message' => 'Default message saved successfully', 'alert_type' => 'success']);
        }

        return redirect()->back()->with(['message' => 'Unable to save default message', 'alert_type' => 'danger']);
    }
}
