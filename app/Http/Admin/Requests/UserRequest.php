<?php

namespace App\Http\Admin\Requests;

use App\Models\User;
use App\Rules\UniqueModel;
use Illuminate\Validation\Rule;

final class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = optional($this->route('user'))->id;

        $rules = [
            'name' => 'nullable|string',
            'lastname' => 'nullable|string',
            'middlename' => 'nullable|string',
            'email' => ['required', 'email:strict', new UniqueModel(User::class, 'email', $id),],
            'login' => ['nullable', 'string', new UniqueModel(User::class, 'login', $id),],
//            'status' => ['required', 'required', Rule::in(User::statusesList('key'))],
            'comment' => 'nullable|string',
        ];

        if ($this->isMethod('post') || $this->input('password')) {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        return  $rules;
    }
}
