<?php

namespace App\Http\Client\Api\Controllers;

use App\Http\Client\Api\Resources\Article\ArticleCategoryListResource;
use App\Http\Client\Api\Resources\Article\ArticleCategoryShowResource;
use App\Http\Client\Api\Resources\Article\ArticleListResource;
use App\Http\Client\Api\Resources\Article\ArticleShowResource;
use App\Http\Client\Controllers\Controller;
use App\Models\Post;
use App\Models\Term;
use Illuminate\Http\Request;

final class ArticleController extends Controller
{
    /**
     * @api {get} /api/articles 01. Список публікацій
     * @apiVersion 1.0.0
     * @apiName ArticleIndex
     * @apiGroup Article
     *
     * @apiParam {Integer} [page] Номер сторінки
     * @apiParam {Integer} [per_page] Кількість на сторінці
     * @apiParam {String} [q] Пошуковий рядок
     * @apiParam {String} [category] Slug категорії: world
     *
     * @apiParam {String=categories,info} [with] Додаткові дані до результату
     *
     */
    public function index(Request $request)
    {
        $posts = Post::query()
            ->with('category', 'media')
            ->whereType(Post::TYPE_ARTICLE)
            ->filterable([], ['vocabulary' => Term::VOCABULARY_ARTICLE_CATEGORIES])->paginate();

        return ArticleListResource::collection($posts)->additional($this->addResources($request));
    }

    /**
     * @api {get} /api/articles/{article:slug} 02. Одна публікація
     * @apiVersion 1.0.0
     * @apiName ArticleShow
     * @apiGroup Article
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "data": {
     *          "id": "a3e22e6-329e-46ba-948f-670dbea5eb1f",
     *          "slug": "sunt-eos-est-ut-commodi-et",
     *          "teaser": "Reprehenderit assumenda suscipit aut quas.",
     *          "body": "<p>Reprehenderit assumenda suscipit aut quas. Impedit minus assumenda autem hic qui.</p>",
     *          "published_at": "2023-09-29T05:58:32.000000Z",
     *        },
     *       "crumbs": [],
     *       "recommends": [],
     *     }
     *
     */
    public function show(Request $request, string $slug)
    {
        /** @var Post $article */
        $article = Post::query()
            ->with('media')
            ->where('slug', $slug)
            ->firstOrFail();

        return ArticleShowResource::make($article)->additional([
            'crumbs' => $article->getBreadcrumbs(),
        ]);
    }

    /**
     * @apiPrivate
     * @api {get} /api/article/categories 03. Список категорій
     * @apiVersion 1.0.0
     * @apiName ArticleCategories
     * @apiGroup Article
     *
     * @apiParam {string} [parent_id] null - всі категорії, 0 - категорії першого рівня, `id` - підкатегорії з категорії id
     *
     */
    public function categories(Request $request)
    {
        $terms = Term::query()
            ->with('media')
            ->whereVocabulary(Term::VOCABULARY_ARTICLE_CATEGORIES)
            ->filterable()->get();

        return ArticleCategoryListResource::collection($terms);
    }

    /**
     * @api {get} /api/articles/categories/{category:slug} 04. Одна категорія
     * @apiVersion 1.0.0
     * @apiName ArticleCategory
     * @apiGroup Article
     *
     */
    public function category(Request $request, Term $category)
    {
        $term = $category->checkAllowed()->load('media');

        return ArticleCategoryShowResource::make($term);
    }

    /**
     * Додаткові дані в ресурси.
     *
     * @param Request $request
     * @return array
     */
    public function addResources(Request $request): array
    {
        $add = [];
        if ($with = filter_explode($request->with)) {
            if (in_array('categories', $with)) {
                $add['categories'] = ArticleCategoryListResource::collection(Term::whereVocabulary(Term::VOCABULARY_ARTICLE_CATEGORIES)->get());
            }
        }

        return $add;
    }
}
