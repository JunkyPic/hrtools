<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\CandidateTest;
use App\Models\Question;
use App\Models\Test;
use App\Models\TestDefaultMessage;
use App\Repository\ImageRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class CandidateController
 *
 * @package App\Http\Controllers
 */
class CandidateController extends Controller
{
    /**
     * @var mixed|null
     */
    private $token = null;

    /**
     * CandidateController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->token = $request->get('t');
    }

    /**
     * @param Request       $request
     * @param CandidateTest $candidate_test_model
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function validateInformation(Request $request, CandidateTest $candidate_test_model)
    {
        if (null === $this->token) {
            return view('front.candidate.error')->with(['token_not_present' => true]);
        }

        try{
            $test_candidate = $candidate_test_model
                ->where('token', $this->token)
                ->first();

            if (null === $test_candidate) {
                return view('front.candidate.error')->with(['error' => 'Invalid token']);
            }

            if ((int)$test_candidate->is_valid !== 1) {
                return redirect()->route('testInvalid');
            }

            if (null !== $test_candidate->started_at) {
                if (time() >= $test_candidate->started_at + $test_candidate->validity) {
                    // test has finished
                    return view('front.candidate.error')->with(
                        [
                            'error' => 'Test has finished, hope you did well',
                        ]
                    );
                }

                return view('front.candidate.error')->with(
                    [
                        'error' => 'Test is already started',
                        'link'  => route('postStartTest', ['t' => $this->token]),
                    ]
                );
            }

        }catch (\Exception $exception){
            return view('front.candidate.error')->with(
                ['error' => 'Something went wrong, contact a system administrator.']
            );
        }

        return redirect()->route('preStartTest', ['t' => $request->get('t')]);
    }

    /**
     * @param \Illuminate\Http\Request  $request
     * @param \App\Models\CandidateTest $candidate_test_model
     * @param \App\Models\Test          $test_model
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function preStartTest(Request $request, CandidateTest $candidate_test_model, Test $test_model)
    {
        if (null === $this->token) {
            return view('front.candidate.error')->with(['token_not_present' => true]);
        }

        try{
            $test_candidate = $candidate_test_model
                ->where('token', $this->token)
                ->first();

            if (null === $test_candidate) {
                return view('front.candidate.error')->with(['error' => 'Invalid token']);
            }

            if ((int)$test_candidate->is_valid !== 1) {
                return redirect()->route('testInvalid');
            }

            if (null !== $test_candidate->started_at) {
                if (time() >= $test_candidate->started_at + $test_candidate->validity) {
                    // test has finished
                    return view('front.candidate.error')->with(
                        [
                            'error' => 'Test has finished, hope you did well',
                        ]
                    );
                }

                return view('front.candidate.error')->with(
                    [
                        'error' => 'Test is already started',
                        'link'  => route('postStartTest', ['t' => $this->token]),
                    ]
                );
            }

            $test_instance = $test_model->where(['id' => $test_candidate->test_id])->first();
            $test_total_time = (int)$test_candidate->validity / 60;

            return view('front.candidate.pre_start_test')->with(
                [
                    'test_total_time' => $test_total_time,
                    'instructions'    => $test_instance->instructions,
                    'test_name'       => $test_instance->name,
                    't'               => $request->get('t'),
                ]
            );

        }catch (\Exception $exception){
            return view('front.candidate.error')->with(
                ['error' => 'Something went wrong, contact a system administrator.']
            );
        }
    }

    /**
     * @param CandidateTest $candidate_test_model
     * @param Test          $test_model
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postStarTest(CandidateTest $candidate_test_model, Test $test_model)
    {
        if ( ! isset($_COOKIE['jsen'])) {
            return view('front.candidate.error')->with(['error' => 'Javascript must be enabled']);
        }

        if (null === $this->token) {
            return view('front.candidate.error')->with(['token_not_present' => true]);
        }

        try{
            $test_candidate = $candidate_test_model
                ->where('token', $this->token)
                ->first();

            if (null === $test_candidate) {
                return view('front.candidate.error')->with(['error' => 'Invalid token']);
            }

            if ((int)$test_candidate->is_valid !== 1) {
                return redirect()->route('testInvalid');
            }

            if (null !== $test_candidate->started_at) {
                if (time() >= $test_candidate->started_at + $test_candidate->validity) {
                    // test has finished
                    return view('front.candidate.error')->with(
                        [
                            'error' => 'Test has finished',
                        ]
                    );
                }
            }

            $start_time = Carbon::now()->addSeconds($test_candidate->validity)->timestamp;

            $test_instance = $test_model
                ->where(['id' => $test_candidate->test_id])
                ->with('chapters.questions.images')
                ->first();

            if (null === $test_candidate->started_at) {
                $test_candidate->started_at = time();
                $test_candidate->save();
            }

            return view('front.candidate.test')->with(
                [
                    'test'       => $test_instance,
                    'start_time' => $start_time,
                    'test_token' => $test_candidate->token,
                ]
            );

        }catch (\Exception $exception){
            return view('front.candidate.error')->with(
                ['error' => 'Something went wrong, contact a system administrator.']
            );
        }
    }

    /**
     * @param Request         $request
     * @param CandidateTest   $candidate_test_model
     * @param Question        $question_model
     * @param Test            $test_model
     * @param ImageRepository $image_repository
     * @param Answer          $answer_model
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postEndTest(
        Request $request,
        CandidateTest $candidate_test_model,
        Question $question_model,
        Test $test_model,
        ImageRepository $image_repository,
        Answer $answer_model
    ){
        try{
            $test_id = $request->get('test_id');
            $answers = $request->get('answers');

            $test_candidate = $candidate_test_model->where(
                [
                    'token'   => $request->get('test_token'),
                    'test_id' => $test_id,
                ]
            )->first();

            if (null === $test_candidate) {
                return view('front.candidate.error')->with(
                    ['error' => 'Something went wrong, contact a system administrator.']
                );
            }

            if ((int)$test_candidate->is_valid !== 1) {
                return redirect()->route('testInvalid');
            }

            $test_model_instance = $test_model->where(['id' => $test_id])->first();

            if (null !== $test_candidate->finished_at) {
                return redirect()->route('testFinished');
            }

            $questions = $question_model->whereIn('id', array_keys($answers))->with('images')->get();

            $folder_name = Str::random('15');
            $original_image_path = \Config::get('image.upload_path');
            $copy_image_path = \Config::get('image.answer_image').$folder_name.DIRECTORY_SEPARATOR;

            foreach ($questions as $question) {
                $answer_instance = $answer_model->create(
                    [
                        'question_title'    => $question->title,
                        'question_body'     => $question->body,
                        'answer'            => null === $answers[$question->id] ? null : $answers[$question->id],
                        'test_name'         => $test_model_instance->name,
                        'candidate_test_id' => $test_candidate->id,
                        'created_at'        => Carbon::now(),
                        'updated_at'        => Carbon::now(),
                    ]
                );

                if ($question->images->count() >= 1) {
                    if ( ! \File::exists($copy_image_path)) {
                        \File::makeDirectory($copy_image_path, $mode = 0777, true, true);
                    }

                    foreach ($question->images as $item) {
                        $answer_instance->images()->create(
                            [
                                'alias'  => $item->alias,
                                'folder' => $folder_name,
                            ]
                        );

                        $image_repository->copy($original_image_path.$item->alias, $copy_image_path.$item->alias);
                    }
                }
            }

            $test_candidate->finished_at = time();
            $test_candidate->is_valid = false;
            $test_candidate->save();

            // Send email with test finished
            $this->sendEmailTestFinished($request->get('test_token'), $candidate_test_model);

            return redirect()->route('testFinished', ['t' => $request->get('test_token')]);

        }catch (\Exception $exception){
            return view('front.candidate.error')->with(
                ['error' => 'Something went wrong, contact a system administrator.']
            );
        }
    }

    private function sendEmailTestFinished($token, CandidateTest $candidate_test_model)
    {
        try{
            $candidate = $candidate_test_model
                ->where('token', $token)
                ->with('candidate')
                ->first();

            \Mail::send(
                'mail.test_finished', // view
                [
                    // data passed to the view
                    'candidate_name' => $candidate->candidate->fullname,
                ],
                function ($m) use ($candidate){
                    $m->to($candidate->candidate->from)->subject(
                        'Candidate '.$candidate->candidate->fullname.' has finished the test'
                    );
                }
            );
        }catch (\Exception $exception){
            // TODO log exception
        }
    }

    /**
     * @param Request       $request
     * @param CandidateTest $candidate_test_model
     *
     * @return JsonResponse
     */
    public function validateDuration(Request $request, CandidateTest $candidate_test_model)
    {
        if ( ! $request->has('t')) {
            return new JsonResponse(
                [
                    'status' => 'PARAM_MISSING',
                ]
            );
        }

        try{
            $test_candidate = $candidate_test_model->where(
                ['token' => $request->get('t')]
            )->first();

            if (null === $test_candidate) {
                return new JsonResponse(
                    [
                        'status' => 'TEST_MISSING',
                    ]
                );
            }

            if (null !== $test_candidate->started_at) {
                if (time() >= $test_candidate->started_at + $test_candidate->validity) {
                    // test has finished
                    return new JsonResponse(
                        [
                            'status' => 'TEST_FINISHED',
                        ]
                    );
                }else {
                    return new JsonResponse(
                        [
                            'status' => 'TEST_ONGOING',
                        ]
                    );
                }
            }else {
                return new JsonResponse(
                    [
                        'status' => 'ARG_MISSING',
                    ]
                );
            }
        }catch (\Exception $exception){
            return new JsonResponse(
                [
                    'status' => 'ERROR',
                ]
            );
        }
    }

    /**
     * @param Request            $request
     * @param CandidateTest      $candidate_test
     * @param Test               $test
     * @param TestDefaultMessage $test_default_message
     *
     * @return $this
     */
    public function testFinished(Request $request, CandidateTest $candidate_test, Test $test, TestDefaultMessage $test_default_message)
    {
        $test_candidate = $candidate_test->where(
            [
                'token'   => $request->get('t'),
            ]
        )->first();

        $test = $test->where('id', $test_candidate->test_id)->first();

        $message = null;

        if(null === $test->end_test_message) {
            $message = $test_default_message->first();
            if(null !== $message) {
                $message = $message->default_message;
            }
        } else {
            $message = $test->end_test_message;
        }

        return view('front.candidate.error')->with(
            [
                'error' => 'Test finished!',
                'message' => $message,
                'title' => 'Test done!',
            ]
        );
    }

    /**
     * @return $this
     */
    public function testInvalid()
    {
        return view('front.candidate.error')->with(
            [
                'error' => 'Test is no longer valid',
                'title' => 'Test invalid',
            ]
        );
    }
}
