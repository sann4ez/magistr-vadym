<?php

namespace App\Http\Auth\Requests;

use App\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email',
            'reset_page_url' => 'required|url',
        ];
    }

    public function prepareForValidation()
    {
        if ($this->get('reset_page_url')) {
            $this->merge([
                'reset_page_url' => trim($this->get('reset_page_url'), '/?')
            ]);
        }
        $this->prepareForValidationPhoneValues('phone');
    }
}