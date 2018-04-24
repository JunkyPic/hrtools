<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserControllerPostChangePassword extends FormRequest
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
      'current_password' => 'required|string',
      'password' => 'required|string|min:6|confirmed|different:current_password',
    ];
  }

  /**
   * @return array
   */
  public function messages() {
    return [
      'password.min' => 'The password must be at least 6 characters long.',
      'password.confirmed' => 'The passwords do not match.',
      'password.required' => 'The password field is required',
      'password.different' => 'The new password cannot be the same as the old password',
      'current_password.required' => 'The current password field is required',
    ];
  }
}
