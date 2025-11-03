<?php

namespace App\Http\Admin\Requests;

use App\Models\Term;

final class TermRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = optional($this->route('term'))->id;

        return [
            'name' => 'required|string|max:255',
            'body' => 'nullable|string',
            'intro_icon' => 'nullable|string',
            'intro_text' => 'nullable|string',
            'is_default' => 'nullable|boolean',
            'vocabulary' => 'required',
            'added' => 'nullable|array',
            'parent_id' => 'nullable|exists:terms,id',
            'google_merchant_id' => 'nullable|string',
            'slug' => 'sometimes|string|unique:terms,id,' . $id,
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->isMethod('post') || $this->has('slug')) {
            if ($slug = $this->slug ?? $this->name) {
                $this->merge([
                    'slug' => Term::slugGenerate($slug, $this->route('term')),
                ]);
            }
        }

    }

    public function getData()
    {
        return $this->only('name', 'slug', 'body', 'intro_icon', 'intro_text', 'is_default', 'added', 'parent_id', 'vocabulary', 'google_merchant_id', 'domain_id');
    }
}
