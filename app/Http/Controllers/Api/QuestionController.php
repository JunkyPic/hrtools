<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Question as QuestionResource;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class QuestionController
 *
 * @package App\Http\Controllers\Api
 */
class QuestionController extends Controller
{
    /**
     * @param Request  $request
     * @param Question $question
     *
     * @return QuestionResource
     */
    public function search(Request $request, Question $question){
        QuestionResource::withoutWrapping();

        return new QuestionResource(
            $question->where('title', 'LIKE', '%' . $request->get('query') . '%')
                ->select(['title', 'id'])
                ->get()
        );
    }
}
