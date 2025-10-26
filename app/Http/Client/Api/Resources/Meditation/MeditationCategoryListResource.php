<?php

namespace App\Http\Client\Api\Resources\Meditation;

use App\Http\Client\Api\Resources\MediaShowResource;
use Illuminate\Http\Resources\Json\JsonResource;

final class MeditationCategoryListResource  extends JsonResource
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
            'slug' => $this->slug,
            'type' => $this->type,
            'name' => $this->name,
            'image' => $this->whenLoaded('media', fn () => MediaShowResource::make($this->getMainMedia('image'))),
            //'logo' => $this->whenLoaded('media', fn () => MediaShowResource::make($this->getMainMedia('logo'))),
            'children' => $this->whenLoaded('children', fn() => MeditationCategoryListResource::collection($this->children)),
        ];
    }
}
