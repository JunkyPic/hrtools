<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionControllerPostCreateQuestion;
use App\Http\Requests\QuestionControllerPostEditQuestion;
use App\Models\Chapter;
use App\Models\Question;
use App\Models\Tag;
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
     * Number of items to show per page
     */
    const PER_PAGE = 5;

    /**
     * @param Request $request
     * @param Chapter $chapter_model
     *
     * @return JsonResponse
     */
    public function questionChapterAssociate(Request $request, Chapter $chapter_model)
    {
        if ( ! $request->has('qid') || ! $request->has('chapter_id') || ! $request->has('type')) {
            return new JsonResponse(
                [
                    'success' => false,
                    'status'  => 500,
                    'message' => 'Parameter missing from job',
                ]
            );
        }

        $qid = $request->get('qid');
        $chapter_id = $request->get('chapter_id');

        try{
            $chapter = $chapter_model->find($chapter_id);
        }catch (\Exception $exception){
            return new JsonResponse(
                [
                    'success' => false,
                    'status'  => 500,
                    'message' => 'Unable to find chapter',
                ]
            );
        }

        if ($request->get('type') == 'add') {
            // Check if the question is already associated with this chapter
            try{
                $associated = $chapter
                    ->questions()
                    ->wherePivot('question_id', '=', $qid)
                    ->wherePivot('chapter_id', '=', $chapter_id)
                    ->first();

                // Question is not associated with chapter
                if (null === $associated) {
                    $chapter->questions()->attach($qid);

                    return new JsonResponse(
                        [
                            'success' => false,
                            'status'  => 200,
                            'message' => 'Question associated successfully to chapter',
                        ]
                    );
                }

                return new JsonResponse(
                    [
                        'success' => false,
                        'status'  => 200,
                        'message' => 'Question is already associated with that chapter',
                    ]
                );

            }catch (\Exception $exception){
                return new JsonResponse(
                    [
                        'success' => false,
                        'status'  => 500,
                        'message' => 'Unable to associate question to chapter',
                    ]
                );
            }
        }

        // Remove question from association with chapter
        try{
            $associated = $chapter
                ->questions()
                ->wherePivot('question_id', '=', $qid)
                ->wherePivot('chapter_id', '=', $chapter_id)
                ->first();

            // Question is not associated with chapter
            if (null === $associated) {
                return new JsonResponse(
                    [
                        'success' => false,
                        'status'  => 200,
                        'message' => 'Question is not associated with that chapter',
                    ]
                );
            }

            $chapter->questions()->detach($qid);

        }catch (\Exception $exception){
            return new JsonResponse(
                [
                    'success' => false,
                    'status'  => 500,
                    'message' => 'Unable to associate question to chapter',
                ]
            );
        }

        return new JsonResponse(
            [
                'success' => true,
                'status'  => 200,
                'message' => 'Question disassociated successfully',
            ]
        );
    }

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
            if ($request->has('tags') && ! empty($request->get('tags'))) {
                $tags = $request->get('tags');
                $question->tags()->attach($tags);
            }

            return redirect()->back()->with(['message' => 'Question created successfully', 'alert_type' => 'success']);
        }

        return redirect()->back()->with(['message' => 'Looks like something went wrong', 'alert_type' => 'danger']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.question.question');
    }

    /**
     * @param Request  $request
     * @param Question $question
     * @param Tag      $tag_model
     * @param Chapter  $chapter_model
     *
     * @return $this
     */
    public function all(Request $request, Question $question, Tag $tag_model, Chapter $chapter_model)
    {
        // Retrieve possible filter list and pass them on
        $tags = $tag_model->select(['id', 'tag'])->get();
        $chapters = $chapter_model->all();

        //check if there's something to filter
        $order = 'DESC';
        $tag = null;
        $chapter = null;

        if ($request->query->has('tag') && 'NA' !== $request->query->get('tag')) {
            $tag = $request->query->get('tag');
        }

        if ($request->query->has('chapter') && 'NA' !== $request->query->get('chapter')) {
            $chapter = $request->query->get('chapter');
        }

        if ($request->query->has('order')) {
            $order = $request->query->get('order');
        }

        $q = $question->with(['images', 'chapters', 'tags']);


        if (null !== $tag) {
            $q = $q->whereHas(
                    'tags',
                    function ($query) use ($tag){
                        $query->where('tags.tag', '=', $tag);
                    }
                );
        }

        if (null !== $chapter) {
            $q = $q->whereHas(
                    'chapters',
                    function ($query) use ($chapter){
                        $query->where('chapter', 'LIKE', '%'.$chapter.'%');
                    }
                );
        }

        $q = $q->orderBy('created_at', $order)->paginate(self::PER_PAGE);
        $links = $q->appends(['order' => $order, 'chapter' => $chapters ?? 'NA', 'tag' => $tag ?? 'NA']);

        return view('admin.question.questions')->with(
            [
                'questions' => $q,
                'tags'      => $tags,
                'tag'       => $tag ?? 'NA',
                'order'     => $order,
                'chapters'  => $chapters,
                'chapter'   => $chapter,
                'links'     => $links,
            ]
        );
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

        if (null === $question) {
            return redirect()->back()->with(
                ['message' => 'Unable to find question in database', 'alert_type' => 'danger']
            );
        }

        $images = $question->images()->get();

        try{
            $image_repository->remove($images);
            $question->images()->detach();
            $question->tags()->detach();
            $question->delete();
        }catch (\Exception $exception){
            return redirect()->back()->with(['message' => 'Unable to delete question']);
        }

        return redirect()->route('questionsAll')->with(['message' => 'Question deleted', 'alert_type' => 'success']);
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

    public function manageTag(Request $request, Question $question)
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
        $question_id = $request->get('qid');

        if ($request->has('remove') && null !== $request->get('remove')) {
            $question = $question->find($question_id);
            $question->tags()->detach($request->get('remove'));

            return new JsonResponse(
                [
                    'success' => true,
                    'status'  => 200,
                    'message' => 'Tag removed successfully',
                ]
            );
        }

        if ($request->has('add') && null !== $request->get('add')) {
            $question = $question->find($question_id);
            $question->tags()->attach($request->get('add'));

            return new JsonResponse(
                [
                    'success' => true,
                    'status'  => 200,
                    'message' => 'Tag added successfully',
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

            if ($request->has('tags') && ! empty($request->get('tags'))) {
                $tags = $request->get('tags');
                $question->tags()->attach($tags);
            }

            return redirect()->back()->with(['message' => 'Question updated successfully', 'alert_type' => 'success']);
        }

        return redirect()->back()->with(['message' => 'Looks like something went wrong', 'alert_type' => 'danger']);
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
            ['question' => $question->with(['images', 'tags'])->where(['id' => $question_id])->first()]
        );
    }
}
