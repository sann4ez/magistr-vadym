<?php

namespace App\Blocks;

use App\Http\Client\Api\Resources\Article\ArticleListResource;
use App\Http\Client\Api\Resources\Meditation\MeditationListResource;
use App\Models\Post;
use Fomvasss\Blocks\Contracts\BlockHandlerInterface;
use Illuminate\Database\Eloquent\Model;

class MeditationsBlockHandler implements BlockHandlerInterface
{
    public static function getType(): string
    {
        return 'meditations';
    }

    public function handle(Model $block, array $attrs = []): array
    {
        $postWith = ['media', 'category'];

        $filter = [
            'sort' => $block->getOptions('sort'),
            'order' => $block->getOptions('order'),
            'limit' => $block->getOptions('limit'),
            'ids' =>  $block->getIds('posts'),
            'type' =>  $block->getOptions('type'),
        ];
        $posts = Post::filterable($filter)->byAllowed()->with($postWith)->get();

        $data['posts'] = MeditationListResource::collection($posts);

        return $data;
    }
}
