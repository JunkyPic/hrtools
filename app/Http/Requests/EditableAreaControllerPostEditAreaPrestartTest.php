<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditableAreaControllerPostEditAreaPrestartTest extends FormRequest
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
