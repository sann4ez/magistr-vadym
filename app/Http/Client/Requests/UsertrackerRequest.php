<?php

namespace App\Http\Client\Requests;

use App\Http\FormRequest;

final class UsertrackerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'emotions'   => 'array',
            'emotions.*' => 'exists:items,id',
            'mood'       => 'required|integer|between:1,10',
            'anxiety'    => 'integer|between:1,10',
            'comment'    => 'nullable|string|max:4096',
        ];
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->only('mood', 'anxiety', 'comment');
    }
}
