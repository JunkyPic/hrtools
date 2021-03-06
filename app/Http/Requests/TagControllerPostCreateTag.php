<?php

namespace App\Http\Requests;

use App\Mapping\RolesAndPermissions;
use Illuminate\Foundation\Http\FormRequest;

class TagControllerPostCreateTag extends FormRequest
{
    /**
     * @return mixed
     */
    public function authorize()
    {
        return \Auth::user()->hasPermissionTo(RolesAndPermissions::PERMISSION_ADD_TAG);
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
