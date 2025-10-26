<?php

namespace App\Http\Client\Api\Resources\Article;

use App\Http\Client\Api\Resources\MediaShowResource;
use Illuminate\Http\Resources\Json\JsonResource;

final class ArticleShowResource  extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'entity' => 'post',
            'type' => \App\Models\Post::TYPE_ARTICLE,
            'name' => $this->name,
            'slug' => $this->slug,
            'body' => $this->body,
            'duration' => $this->duration,
            'states' => $this->getClientStates(),
            'published_at' => $this->getDatetime('published_at'),
            'image' => $this->whenLoaded('media', fn () => MediaShowResource::make($this->getMainMedia('image'))),
            'category' => $this->whenLoaded('category', fn() => ArticleCategoryListResource::make($this->category)),
            'categories' => $this->whenLoaded('categories', fn() => ArticleCategoryListResource::collection($this->categories)),
        ];
    }
}
