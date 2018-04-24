<?php

namespace App\Http\Requests;

use App\Mapping\Roles;
use Illuminate\Foundation\Http\FormRequest;

class TestControllerPostUpdateReview extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return \Auth::user()->hasAnyRole([Roles::ROLE_REVIEWER, Roles::ROLE_ADMIN]);
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
