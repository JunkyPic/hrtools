<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Candidate;
use App\Models\Question;
use App\Models\Test;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class CandidateController
 *
 * @package App\Http\Controllers
 */
class CandidateController extends Controller
{
    public function validateInformation(Request $request, Candidate $candidate_invite_model)
    {
        if ( ! $request->has('t')) {
            return view('front.candidate.error')->with(['token_not_present' => true]);
        }

        try{
            $candidate_invite_model_instance = $candidate_invite_model->where(
                ['test_token' => $request->get('t')]
            )->first();

            if(true == $candidate_invite_model_instance->is_email_token_valid) {
                $candidate_invite_model_instance->is_email_token_valid = false;
                $candidate_invite_model_instance->save();
            }

            if(false == $candidate_invite_model_instance->is_invite_token_valid ) {
                return view('front.candidate.error')->with(['error' => 'Invite expired']);
            }

            if (null === $candidate_invite_model_instance) {
                return view('front.candidate.error')->with(['error' => 'Invalid token']);
            }

            if(null !== $candidate_invite_model_instance->test_finished_at) {
                return redirect()->route('testFinished');
            }

            $created_at = Carbon::parse($candidate_invite_model_instance->created_at);
        }catch (\Exception $exception){
            return view('front.candidate.error')->with(
                ['error' => 'Something went wrong, contact a system administrator.']
            );
            // TODO LOG ERRORS TOO FFS
        }

        if (time() > $candidate_invite_model_instance->invite_validity + $created_at->timestamp) {
            $candidate_invite_model_instance->is_invite_token_valid = false;
            $candidate_invite_model_instance->save();

            return view('front.candidate.error')->with(['error' => 'Invite expired']);
        }

        return redirect()->route('preStartTest', ['t' => $request->get('t')]);
    }

    public function preStartTest(Request $request, Candidate $candidate_invite_model, Test $test_model)
    {
        if ( ! $request->has('t')) {
            return view('front.candidate.error')->with(['token_not_present' => true]);
        }

        try{
            $candidate_invite_model_instance = $candidate_invite_model->where(
                ['test_token' => $request->get('t')]
            )->first();

            if (null === $candidate_invite_model_instance) {
                return view('front.candidate.error')->with(['error' => 'Invalid token']);
            }

            if(null !== $candidate_invite_model_instance->test_finished_at) {
                return redirect()->route('testFinished');
            }

            if (null !== $candidate_invite_model_instance->test_started_at) {
                if(time() >= $candidate_invite_model_instance->test_started_at + $candidate_invite_model_instance->test_validity) {
                    // test has finished
                    return view('front.candidate.error')->with(
                        [
                            'error' => 'Test has finished!',
                        ]
                    );
                }

                return view('front.candidate.error')->with(
                    [
                        'error' => 'Test is already started',
                        'link'  => route('postStartTest', ['t' => $candidate_invite_model_instance->test_token]),
                    ]
                );
            }
        }catch (\Exception $exception){
            return view('front.candidate.error')->with(
                ['error' => 'Something went wrong, contact a system administrator.']
            );
            // TODO LOG ERRORS TOO FFS
        }

        $test_total_time = (int)$candidate_invite_model_instance->test_validity / 60;
        $candidate_name = $candidate_invite_model_instance->to_fullname;

        try{
            $test_instance = $test_model->where(['id' => $candidate_invite_model_instance->test_id])->first();
        }catch (\Exception $exception){
            return view('front.candidate.error')->with(
                ['error' => 'Something went wrong, contact a system administrator.']
            );
        }


        return view('front.candidate.pre_start_test')->with(
            [
                'test_total_time' => $test_total_time,
                'candidate_name'  => $candidate_name,
                'test_name'       => $test_instance->name,
                't'               => $request->get('t'),
            ]
        );
    }

    public function postStarTest(Request $request, Candidate $candidate_invite_model, Test $test_model)
    {
        if ( ! isset($_COOKIE['jsen'])) {
            return view('front.candidate.error')->with(['error' => 'Javascript must be enabled']);
        }

        if ( ! $request->has('t')) {
            return view('front.candidate.error')->with(['token_not_present' => true]);
        }

        try{
            $candidate_invite_model_instance = $candidate_invite_model->where(
                ['test_token' => $request->get('t')]
            )->first();

            if (null === $candidate_invite_model_instance) {
                return view('front.candidate.error')->with(['error' => 'Invalid token']);
            }

            if(null !== $candidate_invite_model_instance->test_finished_at) {
                return redirect()->route('testFinished');
            }

            if (null !== $candidate_invite_model_instance->test_started_at) {
                if(time() >= $candidate_invite_model_instance->test_started_at + $candidate_invite_model_instance->test_validity) {
                    // test has finished
                    return view('front.candidate.error')->with(
                        [
                            'error' => 'Test has finished, hope you did well',
                        ]
                    );
                }
            }

        }catch (\Exception $exception){
            return view('front.candidate.error')->with(
                ['error' => 'Something went wrong, contact a system administrator.']
            );
            // TODO LOG ERRORS TOO FFS
        }


        try{
            $test_instance = $test_model
                ->where(['id' => $candidate_invite_model_instance->test_id])
                ->with('chapters.questions.images')
                ->first();
        }catch (\Exception $exception){
            return view('front.candidate.error')->with(
                ['error' => 'Something went wrong, contact a system administrator.']
            );
        }

        if (null === $candidate_invite_model_instance->test_started_at) {
            $candidate_invite_model_instance->test_started_at = time();
            $candidate_invite_model_instance->save();
        }

        return view('front.candidate.test')->with(
            [
                'test' => $test_instance,
                'test_token' => $candidate_invite_model_instance->test_token,
            ]
        );
    }

    public function postEndTest(Request $request, Candidate $candidate_invite_model, Answer $answer_model, Question $question_model, Test $test_model)
    {
        try{
            $candidate_invite_model_instance = $candidate_invite_model->where(
                [
                    'test_token' => $request->get('test_token'),
                    'test_id' => $request->get('test_id'),
                ]
            )->first();

            $test_model_instance = $test_model->where(['id' => $request->get('test_id')])->first();

            if (null === $candidate_invite_model_instance) {
                return view('front.candidate.error')->with([
                    'error' => 'It looks like something went very wrong' .
                        'We deeply apologize for the inconvenience, but it seems that you are going to have to retake the test. '
                ]);
            }

        }catch (\Exception $exception){
            return view('front.candidate.error')->with([
                'error' => 'It looks like something went very wrong' .
                    'We deeply apologize for the inconvenience, but it seems that you are going to have to retake the test. '
            ]);
            // TODO LOG ERRORS TOO FFS
        }

        if(null !== $candidate_invite_model_instance->test_finished_at) {
            return redirect()->route('testFinished');
        }

        if(!$request->has('answers')) {
            return view('front.candidate.error')->with([
                'error' => 'It looks like something went very wrong' .
                    'We deeply apologize for the inconvenience, but it seems that you are going to have to retake the test. '
            ]);
        }

        // The question ids are the keys and the answers are the values
        $answers = $request->get('answers');
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
                'candidate_id' => $candidate_invite_model_instance->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        $answer_model->insert($answers_insert);

        $candidate_invite_model_instance->test_finished_at = time();
        $candidate_invite_model_instance->is_invite_token_valid = false;
        $candidate_invite_model_instance->is_email_token_valid = false;
        $candidate_invite_model_instance->save();

        return redirect()->route('testFinished');
    }

    public function validateDuration(Request $request, Candidate $candidate_invite_model)
    {
        if ( ! $request->has('t')) {
            return new JsonResponse([
               'status' => 'PARAM_MISSING',
            ]);
        }

        try{
            $candidate_invite_model_instance = $candidate_invite_model->where(
                ['test_token' => $request->get('t')]
            )->first();

            if (null === $candidate_invite_model_instance) {
                return new JsonResponse([
                    'status' => 'TEST_MISSING',
                ]);
            }

            if (null !== $candidate_invite_model_instance->test_started_at) {
                if(time() >= $candidate_invite_model_instance->test_started_at + $candidate_invite_model_instance->test_validity) {
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
            'error' => 'Congratulations you have finished the test!',
            'title' => 'Test done!'
        ]);
    }
}
