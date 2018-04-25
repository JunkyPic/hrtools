<?php

namespace App\Http\Requests;

use App\Mapping\RolesAndPermissions;
use Illuminate\Foundation\Http\FormRequest;

class IssueInvitePostRequest extends FormRequest
{
    /**
     * @return mixed
     */
    public function authorize()
    {
        return \Auth::user()->hasPermissionTo(RolesAndPermissions::PERMISSION_INVITE_USER);
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
            'subject' => 'required|string',
            'body' => 'required|string',
            'validity' => 'required|integer',
        ];
    }

    /**
     * @return array
     */
    public function messages() {
        return [
            'to.required' => 'The "to" field is required',
            'to.email' => 'The email is invalid',
            'subject.required' => 'The subject field is required',
            'body.required' => 'The body field is required',
            'validity.required' => 'The validity field is required',
            'validity.integer' => 'The validity field must be an integer...stop messing with the form smartass',
        ];
    }
}
