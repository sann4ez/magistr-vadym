<?php

namespace App\Http\Auth\Api\Requests;

use App\Http\FormRequest;
use App\Rules\Phone;

class LoginRequest extends FormRequest
{
    public function authenticate()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email'    => ['required', 'string', 'email', 'max:255', 'exists:users'],
            'password' => 'required|string',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->login && strpos($this->login, '@')) {
            $this->merge([
                'login' => $this->login,
                'email' => $this->login,
            ]);
        } elseif ($this->login) {
            $this->merge([
                'login' => $this->login,
                'phone' => $this->login,
            ]);
        }

        $this->prepareForValidationPhoneValues('phone');
    }
}
