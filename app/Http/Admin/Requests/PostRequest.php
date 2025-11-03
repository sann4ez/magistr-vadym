<?php

namespace App\Http\Admin\Requests;

final class PostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'slug' => 'sometimes|string',
            'name' => 'required|string',
            'teaser' => 'nullable|string',
            'body' => 'nullable|string',
            'is_free' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'category_id' => ['sometimes', 'exists:terms,id',],
        ];
    }

    protected function prepareForValidation()
    {
        $this->prepareForValidationDatetimeValues('published_at');
    }

    public function getData()
    {
        $res = $this->only([
            'type',
            'slug',
            'name',
            'teaser',
            'body',
            'is_free',
            'status',
            'published_at',
            'category_id',
        ]);

        if ($this->isMethod('POST')) {
            $res['user_id'] = $this->user()?->id;
        }

        return $res;
    }
}
