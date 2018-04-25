<?php

namespace App\Http\Requests;

use App\Mapping\RolesAndPermissions;
use Illuminate\Foundation\Http\FormRequest;

class ChapterControllerPostCreateChapter extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return \Auth::user()->hasPermissionTo(RolesAndPermissions::PERMISSION_ADD_CHAPTER);
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
