<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Question as QuestionResource;
use App\Models\Question as QuestionModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuestionController extends Controller
{
    public function search(Request $request){
        QuestionResource::withoutWrapping();

        return new QuestionResource(
            QuestionModel::where('title', 'LIKE', '%' . $request->get('query') . '%')
                ->select(['title', 'id'])
                ->get()
        );
    }
}
