<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestControllerPostSubmitReview extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
