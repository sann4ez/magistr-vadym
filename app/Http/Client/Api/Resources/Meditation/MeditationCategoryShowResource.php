<?php

namespace App\Http\Client\Api\Resources\Meditation;

use App\Http\Client\Api\Resources\MediaShowResource;
use Illuminate\Http\Resources\Json\JsonResource;

final class MeditationCategoryShowResource  extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'entity' => $this->vocabulary,
            'type' => 'meditation',
            'slug' => $this->slug,
            'name' => $this->name,
            'body' => $this->body,
            'image' => $this->whenLoaded('media', fn () => MediaShowResource::make($this->getMainMedia('image'))),
        ];
    }
}
