<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InviteEmailRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'unique:users,email|required|email',
            'role_id' => 'required|alpha_num|min:1|max:1',
        ];
    }
}
