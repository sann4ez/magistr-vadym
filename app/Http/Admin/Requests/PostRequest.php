<?php

namespace App\Http\Admin\Requests;

use App\Models\Post;

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
            'sound_id' => ['sometimes', 'required', 'exists:posts,id',],
            //'image' => 'nullable|image'
//            'soun'
        ];
    }

    protected function prepareForValidation()
    {
//        if ($this->isMethod('post') || $this->has('slug')) {
//            if ($slug = $this->slug ?? $this->name) {
//                $model = $this->route('post') ?: $this->route('article') ?: $this->route('meditation') ?: $this->route('breathing') ?: $this->route('sound') ?: $this->route('yoga');
//                    $this->merge([
//                    'slug' => Post::slugGenerate($slug, $model),
//                ]);
//            }
//        }

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
            'sound_id',
            'domain_id',
        ]);

        if ($this->isMethod('POST')) {
            $res['user_id'] = $this->user()?->id;
        }

        return $res;
    }
}
