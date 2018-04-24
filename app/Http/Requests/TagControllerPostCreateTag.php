<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagControllerPostCreateTag extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['tag'] = 'required|max:30|string';
        return $rules;
    }

    /**
     * @return array
     */
    public function messages() {
        return [
            'tag.required' => 'The tag name is required',
            'tag.max' => 'The tag must be at most 30 characters long',
        ];
    }
}
