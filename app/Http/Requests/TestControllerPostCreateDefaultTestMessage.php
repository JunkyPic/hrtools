<?php

namespace App\Http\Requests;

use App\Mapping\RolesAndPermissions;
use Illuminate\Foundation\Http\FormRequest;

class TestControllerPostCreateDefaultTestMessage extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return
            \Auth::user()->hasPermissionTo(RolesAndPermissions::PERMISSION_EDIT_DEFAULT_MESSAGE)
            ||
            \Auth::user()->hasPermissionTo(RolesAndPermissions::PERMISSION_ADD_DEFAULT_MESSAGE);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'default_message' => 'required|string|max:20000',
        ];
    }
}
