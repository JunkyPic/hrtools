<?php

namespace App\Http\Requests;

use App\Mapping\Roles;
use Illuminate\Foundation\Http\FormRequest;

class EditableAreaControllerPostEditAreaPrestartTest extends FormRequest
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
      'instructions' => 'required|string|max:20000',
    ];
  }

  /**
   * @return array
   */
  public function messages() {
    return [
      'instructions.required' => 'The instructions field is required',
      'instructions.max' => 'The instructions field can be 20000 characters at most',
    ];
  }
}
