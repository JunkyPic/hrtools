<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Candidate;
use App\Models\CandidateTest;
use App\Models\Question;
use App\Models\Test;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        if ( null === $this->token) {
            return view('front.candidate.error')->with(['token_not_present' => true]);
        }

        try{
            $test_candidate = $candidate_test_model
                ->where('token', $this->token)
                ->first();

            if (null === $test_candidate) {
                return view('front.candidate.error')->with(['error' => 'Invalid token']);
            }

            if((int) $test_candidate->is_valid !== 1) {
                return redirect()->route('testInvalid');
            }

            if (null !== $test_candidate->started_at) {
                if(time() >= $test_candidate->started_at + $test_candidate->validity) {
                    // test has finished
                    return view('front.candidate.error')->with([
                            'error' => 'Test has finished, hope you did well',
                        ]
                    );
                }
                return view('front.candidate.error')->with([
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

    public function preStartTest(Request $request, CandidateTest $candidate_test_model, Test $test_model)
    {
        if ( null === $this->token) {
            return view('front.candidate.error')->with(['token_not_present' => true]);
        }

        try{
            $test_candidate = $candidate_test_model
                ->where('token', $this->token)
                ->first();

            if (null === $test_candidate) {
                return view('front.candidate.error')->with(['error' => 'Invalid token']);
            }

            if((int) $test_candidate->is_valid !== 1) {
                return redirect()->route('testInvalid');
            }

            if (null !== $test_candidate->started_at) {
                if(time() >= $test_candidate->started_at + $test_candidate->validity) {
                    // test has finished
                    return view('front.candidate.error')->with([
                            'error' => 'Test has finished, hope you did well',
                        ]
                    );
                }

                return view('front.candidate.error')->with([
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

    public function postStarTest(CandidateTest $candidate_test_model, Test $test_model)
    {
        if ( ! isset($_COOKIE['jsen'])) {
            return view('front.candidate.error')->with(['error' => 'Javascript must be enabled']);
        }

        if ( null === $this->token) {
            return view('front.candidate.error')->with(['token_not_present' => true]);
        }

        try{
            $test_candidate = $candidate_test_model
                ->where('token', $this->token)
                ->first();

            if (null === $test_candidate) {
                return view('front.candidate.error')->with(['error' => 'Invalid token']);
            }

            if((int) $test_candidate->is_valid !== 1) {
                return redirect()->route('testInvalid');
            }

            if (null !== $test_candidate->started_at) {
                if(time() >= $test_candidate->started_at + $test_candidate->validity) {
                    // test has finished
                    return view('front.candidate.error')->with([
                            'error' => 'Test has finished',
                        ]
                    );
                }
                return view('front.candidate.error')->with([
                        'error' => 'Test is already started',
                        'link'  => route('postStartTest', ['t' => $this->token]),
                    ]
                );
            }
            
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
                    'test' => $test_instance,
                    'test_token' => $test_candidate->token,
                ]
            );

        }catch (\Exception $exception){
            return view('front.candidate.error')->with(
                ['error' => 'Something went wrong, contact a system administrator.']
            );
        }
    }

    public function postEndTest(Request $request, CandidateTest $candidate_test_model, Answer $answer_model, Question $question_model, Test $test_model)
    {
        try{
            $test_id = $request->get('test_id');
            $answers = $request->get('answers');

            $test_candidate = $candidate_test_model->where(
                [
                    'token' => $request->get('test_token'),
                    'test_id' => $test_id,
                ]
            )->first();

            if (null === $test_candidate) {
                return view('front.candidate.error')->with(
                    ['error' => 'Something went wrong, contact a system administrator.']
                );
            }

            if((int) $test_candidate->is_valid !== 1) {
                return redirect()->route('testInvalid');
            }

            $test_model_instance = $test_model->where(['id' => $test_id])->first();

            if(null !== $test_candidate->finished_at) {
                return redirect()->route('testFinished');
            }
            $questions = $question_model->whereIn('id', array_keys($answers))->get();

            $answers_insert = [];

            foreach($questions as $question) {
                $answers_insert[] = [
                    'question_title' => $question->title,
                    'question_body' => $question->body,
                    'answer' => null === $answers[$question->id] ? null : $answers[$question->id] ,
                    'is_correct' => null,
                    'comment' => null,
                    'question_id' => $question->id,
                    'test_id' => $test_model_instance->id,
                    'test_name' => $test_model_instance->name,
                    'candidate_id' => $test_candidate->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }

            $answer_model->insert($answers_insert);

            $test_candidate->finished_at = time();
            $test_candidate->is_valid = false;
            $test_candidate->save();

            return redirect()->route('testFinished');

        }catch (\Exception $exception) {
            return view('front.candidate.error')->with(
                ['error' => 'Something went wrong, contact a system administrator.']
            );
        }
    }

    public function validateDuration(Request $request, CandidateTest $candidate_test_model)
    {
        if ( ! $request->has('t')) {
            return new JsonResponse([
               'status' => 'PARAM_MISSING',
            ]);
        }

        try{
            $test_candidate = $candidate_test_model->where(
                ['token' => $request->get('t')]
            )->first();

            if (null === $test_candidate) {
                return new JsonResponse([
                    'status' => 'TEST_MISSING',
                ]);
            }

            if (null !== $test_candidate->started_at) {
                if(time() >= $test_candidate->started_at + $test_candidate->validity) {
                    // test has finished
                    return new JsonResponse([
                        'status' => 'TEST_FINISHED',
                    ]);
                } else{
                    return new JsonResponse([
                        'status' => 'TEST_ONGOING',
                    ]);
                }
            } else {
                return new JsonResponse([
                    'status' => 'ARG_MISSING',
                ]);
            }
        }catch (\Exception $exception){
            return new JsonResponse([
                'status' => 'ERROR',
            ]);
            // TODO LOG ERRORS TOO FFS
        }
    }

    public function testFinished() {
        return view('front.candidate.error')->with([
            'error' => 'Test finished!',
            'title' => 'Test done!'
        ]);
    }

    public function testInvalid() {
        return view('front.candidate.error')->with([
            'error' => 'Test is no longer valid',
            'title' => 'Test invalid'
        ]);
    }
}
