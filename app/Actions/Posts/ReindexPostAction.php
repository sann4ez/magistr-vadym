<?php

namespace App\Actions\Posts;

use App\Models\Post;
use Lorisleiva\Actions\Concerns\AsAction;

final class ReindexPostAction
{
    use AsAction;

    public function handle(Post $post)
    {
        $categoryAncestorsIds = $post->category?->ancestors->pluck('id')->toArray() ?: [];
        $categoriesIds = $post->categories->pluck('id')->toArray();
        $categoriesIds[] = $post->category_id;
        $categoriesIds = array_merge(\array_unique($categoriesIds), $categoryAncestorsIds);

        $translateIndexes = [];
//        $locales = array_keys($post->getTranslationsArray());
        $locales = ['uk'] /*\Domain::getSupportedLocalesCodes()*/;

        foreach ($locales as $key) {
            $name = $post->getTranslationsArray()[$key]['name'] ?? '';
            $translateIndexes['name_'.$key] = mb_strtolower($name);
            //$body = strip_json($post->getTranslationsArray()[$key]['body'] ?? '');
            // В звуках немає опису та короткого опису
            //$teaser = $post->getTranslationsArray()[$key]['teaser'] ?? '';
            $translateIndexes['q_'.$key] = mb_strtolower($name/* . ' ' . $teaser . ' ' . $body*/);
        }

        $post->setAttribute('index', [
            'tags' => $post->tags?->pluck('id')->toArray() ?: [],
            'categories' => $categoriesIds,
            'created_at' => $post->created_at?->timestamp,
            'published_at' => $post->published_at?->timestamp,
        ] + $translateIndexes)->saveQuietly();
    }
}
