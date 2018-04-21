<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChapterControllerPostCreateChapter;
use App\Http\Requests\ChapterControllerPostEditChapter;
use App\Models\Chapter;

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

        try{
            $chapter->update([
               'chapter' => $request->get('chapter'),
               'information' => $request->get('information'),
            ]);
        } catch (\Exception $exception) {
            return redirect()->back()->with(['message' => 'Unable to update chapter', 'alert_type' => 'danger']);
        }

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
        try{
            $chapter_model->create([
                'chapter' => $request->get('chapter'),
                'information' => $request->has('information') ? $request->get('information') : null,
            ]);
        }catch (\Exception $exception) {
            return redirect()->back()->with(['message' => 'Unable to create chapter', 'alert_type' => 'danger']);
        }

        return redirect()->back()->with(['message' => 'Chapter created successfully', 'alert_type' => 'success']);
    }

    /**
     * @param         $chapter_id
     * @param Chapter $chapter_model
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($chapter_id, Chapter $chapter_model) {
        $chapter = $chapter_model->where(['id' => $chapter_id])->first();
        try{
            $chapter->delete();
        } catch (\Exception $exception) {
            return redirect()->back()->with(['message' => 'Unable to delete chapter', 'alert_type' => 'danger']);
        }

        return redirect()->route('chapterAll')->with(['message' => 'Chapter deleted successfully', 'alert_type' => 'success']);
    }

    /**
     * @param Chapter $chapter_model
     *
     * @return $this
     */
    public function all(Chapter $chapter_model) {
        return view('admin.chapter.chapters')->with(['chapters' => $chapter_model->paginate(10)]);
    }
}
