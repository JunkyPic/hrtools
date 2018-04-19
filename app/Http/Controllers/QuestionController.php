<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionControllerPostCreateQuestion;
use App\Models\Question;
use App\Repository\ImageRepository;

class QuestionController extends Controller
{
    public function add(QuestionControllerPostCreateQuestion $request, ImageRepository $image_repository) {
        if($request->files->has('images')) {
            $images = $image_repository->store($request->files->get('images'));
            $image_ids = [];
            foreach($images as $image) {
                $image_ids[] = $image->id;
            }
        }

        if($question = Question::create($request->except(['_token', 'images']))) {
            if(isset($image_ids)) {
                $question->images()->attach($image_ids);
            }

            return redirect()->back()->with(['message' => 'Question created successfully']);
        }
        return redirect()->back()->with(['message' => 'Looks like something went wrong']);
    }

    public function create() {
        return view('admin.question');
    }

    public function all() {
        return view('admin.questions')->with(['questions' => Question::with('images')->paginate(10)]);
    }

    public function delete($question_id) {

    }

    public function update($question_id) {

    }
}
