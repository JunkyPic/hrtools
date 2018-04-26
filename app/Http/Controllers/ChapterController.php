<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChapterControllerPostCreateChapter;
use App\Http\Requests\ChapterControllerPostEditChapter;
use App\Mapping\RolesAndPermissions;
use App\Models\Chapter;
use App\Models\Test;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ChapterController
 *
 * @package App\Http\Controllers
 */
class ChapterController extends Controller
{
    /**
     * @param         $chapter_id
     * @param Chapter $chapter_model
     *
     * @return $this
     */
    public function getEdit($chapter_id, Chapter $chapter_model) {
        $chapter = $chapter_model->where(['id' => $chapter_id])->first();

        return view('admin.chapter.edit')->with(['chapter' => $chapter]);
    }

    /**
     * @param                                  $chapter_id
     * @param ChapterControllerPostEditChapter $request
     * @param Chapter                          $chapter_model
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit($chapter_id, ChapterControllerPostEditChapter $request, Chapter $chapter_model) {
        $chapter = $chapter_model->where(['id' => $chapter_id])->first();

        $chapter->update([
           'chapter' => $request->get('chapter'),
           'information' => $request->get('information'),
        ]);

        return redirect()->back()->with(['message' => 'Chapter updated successfully', 'alert_type' => 'success']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate() {
        return view('admin.chapter.create');
    }

    /**
     * @param ChapterControllerPostCreateChapter $request
     * @param Chapter                            $chapter_model
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreate(ChapterControllerPostCreateChapter $request, Chapter $chapter_model) {
        $chapter_model->create([
            'chapter' => $request->get('chapter'),
            'information' => $request->has('information') ? $request->get('information') : null,
        ]);

        return redirect()->back()->with(['message' => 'Chapter created successfully', 'alert_type' => 'success']);
    }

  /**
   * @param                                  $chapter_id
   * @param \App\Mapping\RolesAndPermissions $roles_and_permissions
   * @param \App\Models\Chapter              $chapter_model
   *
   * @return \Illuminate\Http\RedirectResponse
   * @throws \Illuminate\Auth\Access\AuthorizationException
   */
    public function delete($chapter_id, RolesAndPermissions $roles_and_permissions, Chapter $chapter_model) {
      if(!$roles_and_permissions->hasPermissionTo(RolesAndPermissions::PERMISSION_DELETE_CHAPTER)) {
        throw new AuthorizationException();
      }

        $chapter = $chapter_model->where(['id' => $chapter_id])->first();
        $chapter->delete();

        return redirect()->route('chapterAll')->with(['message' => 'Chapter deleted successfully', 'alert_type' => 'success']);
    }

    /**
     * @param Chapter $chapter_model
     * @param Test    $test_model
     *
     * @return $this
     */
    public function all(Chapter $chapter_model, Test $test_model) {
        return view('admin.chapter.chapters')->with([
            'chapters' => $chapter_model->with(['tests'])->paginate(10),
            'tests' => $test_model->get(),
        ]);
    }

    /**
     * @param Request $request
     * @param Test    $test_model
     *
     * @return JsonResponse
     */
    public function chapterTestAssociate(Request $request, Test $test_model) {
        // TODO: Set message form chapter/question dissasociation near each chapter/question
        if ( ! $request->has('chapter_id') || ! $request->has('test_id') || ! $request->has('type')) {
            return new JsonResponse(
                [
                    'success' => false,
                    'status'  => 500,
                    'message' => 'Parameter missing from job',
                ]
            );
        }

        $chapter_id = $request->get('chapter_id');
        $test_id = $request->get('test_id');

        try{
            $test = $test_model->find($test_id);
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
            // Check if the chapter is already associated with this test
            try{
                $associated = $test
                    ->chapters()
                    ->wherePivot('test_id', '=', $test_id)
                    ->wherePivot('chapter_id', '=', $chapter_id)
                    ->first();

                // Question is not associated with chapter
                if (null === $associated) {
                    $test->chapters()->attach($chapter_id);

                    return new JsonResponse(
                        [
                            'success' => false,
                            'status'  => 200,
                            'message' => 'Chapter associated successfully to test',
                        ]
                    );
                }

                return new JsonResponse(
                    [
                        'success' => false,
                        'status'  => 200,
                        'message' => 'Chapter is already associated with that test',
                    ]
                );

            }catch (\Exception $exception){
                return new JsonResponse(
                    [
                        'success' => false,
                        'status'  => 500,
                        'message' => 'Unable to associate chapter to test',
                    ]
                );
            }
        }

        // Remove chapter from association with test
        try{
            $associated = $test
                ->chapters()
                ->wherePivot('test_id', '=', $test_id)
                ->wherePivot('chapter_id', '=', $chapter_id)
                ->first();

            // Question is not associated with chapter
            if (null === $associated) {
                return new JsonResponse(
                    [
                        'success' => false,
                        'status'  => 200,
                        'message' => 'Chapter is not associated with that test',
                    ]
                );
            }

            $test->chapters()->detach($chapter_id);

        }catch (\Exception $exception){
            return new JsonResponse(
                [
                    'success' => false,
                    'status'  => 500,
                    'message' => 'Unable to associate chapter to test',
                ]
            );
        }

        return new JsonResponse(
            [
                'success' => true,
                'status'  => 200,
                'message' => 'Chapter disassociated from test successfully',
            ]
        );
    }
}
