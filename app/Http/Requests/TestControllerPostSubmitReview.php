<?php

namespace App\Http\Requests;

use App\Mapping\RolesAndPermissions;
use Illuminate\Foundation\Http\FormRequest;

class TestControllerPostSubmitReview extends FormRequest
{
    /**
     * @return mixed
     */
    public function authorize()
    {
        return \Auth::user()->hasPermissionTo(RolesAndPermissions::PERMISSION_ADD_REVIEW);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'is_correct'=> 'required',
        ];
    }

    /**
     * @return array
     */
    public function messages() {
        return [
            'is_correct.required' => 'All answers must have a status(Correct, Incorrect, etc)',
        ];
    }
}
