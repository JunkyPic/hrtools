<?php

namespace App\Http\Requests;

use App\Mapping\RolesAndPermissions;
use Illuminate\Foundation\Http\FormRequest;

class TestControllerPostCreateTest extends FormRequest
{
    /**
     * @return mixed
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
        return [
            'name' => 'required|string|max:300',
            'instructions'=> 'required|string|max:20000',
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
            'instructions.max' => 'The chapter name must be at most 20000 characters',
            'instructions.required' => 'The instructions field is required',
        ];
    }
}
