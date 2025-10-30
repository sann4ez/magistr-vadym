<?php

namespace App\Blocks;

use App\Http\Client\Api\Resources\Article\ArticleListResource;
use App\Models\Post;
use Fomvasss\Blocks\Contracts\BlockHandlerInterface;
use Illuminate\Database\Eloquent\Model;

class ArticlesBlockHandler implements BlockHandlerInterface
{
    public static function getType(): string
    {
        return 'articles';
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

        $data['posts'] = ArticleListResource::collection($posts);

        return $data;
    }
}
