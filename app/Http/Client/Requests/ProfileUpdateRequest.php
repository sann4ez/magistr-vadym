<?php

namespace App\Http\Client\Requests;

use App\Http\FormRequest;
use App\Models\User;
use App\Rules\Email;
use App\Rules\UniqueModel;

final class ProfileUpdateRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->user()?->id;

        $res = [
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'email:strict', new UniqueModel(User::class, 'email', $id), 'min:3', 'max:200', new Email()],
            'phone' => ['sometimes', 'string', new UniqueModel(User::class, 'phone', $id)],
            'birthday' => ['sometimes', 'date'],
        ];

        return $res;
    }
}
