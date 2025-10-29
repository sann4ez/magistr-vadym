<?php

namespace App\Http\Client\Api\Resources\Meditation;

use App\Http\Client\Api\Resources\MediaShowResource;
use App\Http\Client\Api\Resources\Sound\SoundListResource;
use App\Models\Post;
use Illuminate\Http\Resources\Json\JsonResource;

final class MeditationShowResource  extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'entity' => 'post',
            'type' => Post::TYPE_MEDITATION,
            'name' => $this->name,
            'slug' => $this->slug,
            'teaser' => $this->getTeaser(),
            'is_free' => $this->isFree(),
            'states' => $this->getClientStates(),
            'duration' => $this->duration,
            'published_at' => $this->getDatetime('published_at'),

            'category' => $this->whenLoaded('category', fn() => MeditationCategoryListResource::make($this->category)),

            'image' => $this->whenLoaded('media', fn () => MediaShowResource::make($this->getMainMedia('image'))),
            'video' => $this->whenLoaded('media', fn () => MediaShowResource::make($this->getMainMedia('video'))),
        ];
    }
}
