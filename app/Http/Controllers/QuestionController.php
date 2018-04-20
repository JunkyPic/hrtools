<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionControllerPostCreateQuestion;
use App\Http\Requests\QuestionControllerPostEditQuestion;
use App\Models\Question;
use App\Repository\ImageRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class QuestionController
 *
 * @package App\Http\Controllers
 */
class QuestionController extends Controller
{
    /**
     * @param QuestionControllerPostCreateQuestion $request
     * @param ImageRepository                      $image_repository
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(QuestionControllerPostCreateQuestion $request, ImageRepository $image_repository)
    {
        if ($request->files->has('images')) {
            $images = $image_repository->store($request->files->get('images'));
            $image_ids = [];
            foreach ($images as $image) {
                $image_ids[] = $image->id;
            }
        }

        if ($question = Question::create($request->except(['_token', 'images']))) {
            if (isset($image_ids)) {
                $question->images()->attach($image_ids);
            }

            return redirect()->back()->with(['message' => 'Question created successfully']);
        }

        return redirect()->back()->with(['message' => 'Looks like something went wrong']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.question.question');
    }

    /**
     * @return $this
     */
    public function all()
    {
        return view('admin.question.questions')->with(['questions' => Question::with('images')->orderBy('created_at', 'DESC')->paginate(10)]);
    }

    /**
     * @param                 $question_id
     * @param Question        $question
     * @param ImageRepository $image_repository
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete($question_id, Question $question, ImageRepository $image_repository)
    {
        $question = $question->find($question_id);

        if(null === $question) {
            return redirect()->back()->with(['message' => 'Unable to find question in database']);
        }

        $images = $question->images()->get();

        $image_repository->remove($images);
        $question->images()->detach();

        try {
            $question->delete();
        }catch (\Exception $exception) {
            return redirect()->back()->with(['message' => 'Unable to delete question']);
        }

        return redirect()->route('questionsAll')->with(['message' => 'Question deleted']);

    }

    /**
     * @param Request         $request
     * @param Question        $question
     * @param ImageRepository $image_repository
     *
     * @return JsonResponse
     */
    public function updateImages(Request $request, Question $question, ImageRepository $image_repository)
    {
        if ( ! $request->has('qid')) {
            return new JsonResponse(
                [
                    'success' => false,
                    'status'  => 500,
                    'message' => 'Parameter missing from job',
                ]
            );
        }

        if ( ! $request->has('ids')) {
            return new JsonResponse(
                [
                    'success' => false,
                    'status'  => 500,
                    'message' => 'No images selected',
                ]
            );
        }

        if (empty($request->get('ids'))) {
            return new JsonResponse(
                [
                    'success' => false,
                    'status'  => 304,
                    'message' => 'Not modified',
                ]
            );
        }

        $question = $question->find($request->get('qid'));

        $image_repository->remove($question->images()->wherePivotIn('image_id', $request->get('ids'))->get());

        if ($question->images()->detach($request->get('ids'))) {

            return new JsonResponse(
                [
                    'success' => true,
                    'ids'     => $request->get('ids'),
                    'message' => 'Image(s) successfully removed',
                ]
            );
        }

        return new JsonResponse(
            [
                'success' => false,
                'status'  => 500,
                'message' => 'Something went wrong',
            ]
        );
    }

    /**
     * @param                                    $question_id
     * @param QuestionControllerPostEditQuestion $request
     * @param Question                           $question
     * @param ImageRepository                    $image_repository
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(
        $question_id,
        QuestionControllerPostEditQuestion $request,
        Question $question,
        ImageRepository $image_repository
    ){
        if ($request->files->has('images')) {
            $images = $image_repository->store($request->files->get('images'));
            $image_ids = [];
            foreach ($images as $image) {
                $image_ids[] = $image->id;
            }
        }

        $question = $question->find($question_id);

        if ($question->update($request->except(['_token', 'images']))) {
            if (isset($image_ids)) {
                $question->images()->attach($image_ids);
            }

            return redirect()->back()->with(['message' => 'Question updated successfully']);
        }

        return redirect()->back()->with(['message' => 'Looks like something went wrong']);
    }

    /**
     * @param          $question_id
     * @param Question $question
     *
     * @return $this
     */
    public function edit($question_id, Question $question)
    {
        return view('admin.question.edit')->with(
            ['question' => $question->with(['images'])->where(['id' => $question_id])->first()]
        );
    }
}
