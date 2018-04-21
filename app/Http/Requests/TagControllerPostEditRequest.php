<?php

namespace App\Http\Requests;

use App\Models\Tag;
use Illuminate\Foundation\Http\FormRequest;

class TagControllerPostEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // TODO add gate here
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
