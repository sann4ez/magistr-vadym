<?php

namespace App\Http\Client\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $fullUrl = $this->getFullUrl(isset($this->generated_conversions['optimize']) ? 'optimize' : '');

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'collection_name' => $this->collection_name,
            'mime_type' => $this->mime_type,
            'extension' => $this->extension,
            'name' => $this->name,
            'size' => $this->size,
            'url' => $fullUrl,
            'is_main' => boolval($this->is_main),
            'delete' => false,
        ];
    }
}
