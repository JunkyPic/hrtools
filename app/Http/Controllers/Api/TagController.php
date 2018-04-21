<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Tag;
use App\Models\Tag as TagModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class TagController
 *
 * @package App\Http\Controllers\Api
 */
class TagController extends Controller
{
    /**
     * @param Request  $request
     * @param TagModel $tag_model
     *
     * @return Tag
     */
    public function search(Request $request, TagModel $tag_model) {
        Tag::withoutWrapping();

        return new Tag(
            $tag_model->where('tag', 'LIKE', '%' . $request->get('query') . '%')
                ->select(['tag', 'id'])
                ->get()
        );
    }
}
