<?php

namespace App\Http\Auth\Requests;

use App\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function rules()
    {
        $res = [
            'email' => 'required|email',
            'token' => 'required_without:code|string',
            'code' => 'required_without:token|string',
            'password' => ['required', 'string', \Illuminate\Validation\Rules\Password::min(8), 'max:255'],
        ];

        if ($this->has('password_confirmation')) {
            $res['password'] = ['required', 'string', \Illuminate\Validation\Rules\Password::min(8), 'max:255', 'confirmed'];
        }

        return $res;
    }
}
