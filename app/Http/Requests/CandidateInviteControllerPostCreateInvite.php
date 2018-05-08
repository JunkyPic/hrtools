<?php

namespace App\Http\Requests;

use App\Mapping\RolesAndPermissions;
use Illuminate\Foundation\Http\FormRequest;

class CandidateInviteControllerPostCreateInvite extends FormRequest
{

  /**
   * @return bool
   * @throws \Exception
   */
    public function authorize()
    {
        return \Auth::user()->hasPermissionTo(RolesAndPermissions::PERMISSION_INVITE_CANDIDATE);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'to' => 'required|string|email',
            'phone' => 'required',
            'fullname' => 'required|string',
            'subject' => 'required|string',
            'message' => 'required|string',
            'test_validity' => 'required|integer',
            'test_id' => 'required|integer',
        ];
    }

    /**
     * @return array
     */
    public function messages() {
        return [
            'to.required' => 'The "to" field is required',
            'phone.required' => 'The phone field is required',
            'to.email' => 'The email is invalid',
            'subject.required' => 'The subject field is required',
            'message.required' => 'The body field is required',
            'test_validity.required' => 'The test validity field is required',
            'test_id.required' => 'The test validity field is required',
            'test_validity.integer' => 'The test validity field must be an integer...stop messing with the form smartass',
            'test_id.integer' => 'The test validity field must be an integer...stop messing with the form smartass',
        ];
    }
}
