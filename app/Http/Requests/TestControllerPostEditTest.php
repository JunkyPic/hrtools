<?php

namespace App\Http\Requests;

use App\Mapping\Roles;
use Illuminate\Foundation\Http\FormRequest;

class TestControllerPostEditTest extends FormRequest
{
    /**
     * @return mixed
     */
    public function authorize()
    {
        return \Auth::user()->hasAnyRole([Roles::ROLE_CONTENT_CREATOR, Roles::ROLE_ADMIN]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=> 'required|string|max:300',
            'information' => 'sometimes|nullable|string|max:10000',
        ];
    }

    /**
     * @return array
     */
    public function messages() {
        return [
            'name.required' => 'The chapter name field is required',
            'name.max' => 'The chapter name must be at most 300 characters',
            'information.max' => 'The chapter name must be at most 10000 characters',
        ];
    }
}
