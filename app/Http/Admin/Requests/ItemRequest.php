<?php

namespace App\Http\Admin\Requests;

use App\Models\Item;
use Illuminate\Validation\Rule;

final class ItemRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $item = optional($this->route('item'));

        return [
            'key' => ['sometimes', 'max:255', 'regex:/^[a-zA-Z0-9_-]+$/', Rule::unique('items')->where('type', $item->type ?: $this->input('type'))->ignore($item->id),],
            'name' => 'sometimes|string|max:255',
            'title' => 'sometimes|string|max:255',
            'desc' => 'nullable|string|max:10000',
            'icon' => 'nullable|string|max:255',
            'type' => ['required', 'string', Rule::in(Item::typesList('key'))],
            'color' => 'nullable|string|max:255',
            'bg' => 'nullable|string|max:255',
            'weight' => 'nullable|numeric|max:10000',
        ];
    }

    public function getData(): array
    {
        return $this->only('key', 'name', 'title', 'desc', 'type', 'icon', 'color', 'bg',);
    }
}
