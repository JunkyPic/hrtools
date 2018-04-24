<?php

namespace App\Http\Requests;

use App\Mapping\Roles;
use App\Models\Tag;
use Illuminate\Foundation\Http\FormRequest;

class TagControllerPostEditRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return \Auth::user()->hasAnyRole([Roles::ROLE_CONTENT_CREATOR, Roles::ROLE_ADMIN]);
    }

    /**
     * @param Tag $tag_model
     *
     * @return mixed
     */
    public function rules(Tag $tag_model)
    {
        $tag = $tag_model->where(['id' => $this->route('id')])->first();

        $rules['tag'] = 'required|max:10|string|unique:tags,tag,'.$tag->tag;
        return $rules;
    }

    /**
     * @return array
     */
    public function messages() {
        return [
            'tag.required' => 'The tag name is required',
            'tag.unique' => 'The tag already exists or the name was not modified',
            'tag.max' => 'The tag must be at most 10 characters long',
        ];
    }
}
