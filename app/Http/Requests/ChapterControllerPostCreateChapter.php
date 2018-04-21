<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChapterControllerPostCreateChapter extends FormRequest
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
            'chapter' => 'required|string|max:300',
            'information' => 'sometimes|nullable|string|max:10000',
        ];
    }

    /**
     * @return array
     */
    public function messages() {
        return [
            'chapter.required' => 'The chapter name field is required',
            'chapter.max' => 'The chapter name must be at most 300 characters',
            'information.max' => 'The chapter name must be at most 10000 characters',
        ];
    }
}
