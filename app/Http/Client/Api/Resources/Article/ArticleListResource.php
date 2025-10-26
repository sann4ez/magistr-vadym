<?php

namespace App\Http\Client\Api\Resources\Article;

use App\Http\Client\Api\Resources\MediaShowResource;
use App\Http\Client\Api\Resources\Terms\TermSimpleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'type' => $this->type,
            'name' => $this->name,
            'teaser' => $this->getTeaser(),
            'duration' => $this->duration,
            'is_free' => $this->isFree(),
            'states' => $this->getClientStates(),
            'published_at' => $this->getDatetime('published_at'),

            'category' => $this->whenLoaded('category', fn () => TermSimpleResource::make($this->category)),
            'image' => $this->whenLoaded('media', fn () => MediaShowResource::make($this->getMainMedia('image'))),
        ];
    }
}
