<?php

namespace App\Blocks;

use Fomvasss\Blocks\Contracts\BlockHandlerInterface;

class PostsBlockHandler extends ArticlesBlockHandler implements BlockHandlerInterface
{
    public static function getType(): string
    {
        return 'posts';
    }
}