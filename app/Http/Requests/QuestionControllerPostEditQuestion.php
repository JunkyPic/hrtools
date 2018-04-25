<?php

namespace App\Http\Requests;

use App\Mapping\RolesAndPermissions;
use Illuminate\Foundation\Http\FormRequest;

class QuestionControllerPostEditQuestion extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return \Auth::user()->hasAnyRole([RolesAndPermissions::ROLE_CONTENT_CREATOR, RolesAndPermissions::ROLE_ADMIN]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['title'] = 'required|max:200|string';
        $rules['body'] = 'required|max:20000|string';

        if($this->files->has('images') && !empty($this->files->get('images')))  {
            $rules['images.*'] = 'image|mimes:jpeg,jpg,png,gif,webm|max:7000';
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function messages() {
        return [
            'images.*' => 'The image(s) must be of type jpeg, jpg, png with a maximum size of 7MB',
            'title.required' => 'The title field is required',
            'title.body' => 'The body field is required',
        ];
    }
}
