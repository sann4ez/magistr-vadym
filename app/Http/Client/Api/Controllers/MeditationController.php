<?php

namespace App\Http\Client\Api\Controllers;

use App\Http\Client\Api\Resources\Meditation\MeditationCategoryListResource;
use App\Http\Client\Api\Resources\Meditation\MeditationCategoryShowResource;
use App\Http\Client\Api\Resources\Meditation\MeditationListResource;
use App\Http\Client\Api\Resources\Meditation\MeditationShowResource;
use App\Http\Client\Controllers\Controller;
use App\Models\Post;
use App\Models\Term;
use Illuminate\Http\Request;

final class MeditationController extends Controller
{
    /**
     * @api {get} /api/meditations 01. Список медитацій
     * @apiVersion 1.0.0
     * @apiName MeditationIndex
     * @apiGroup Meditation
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
        $meditations = Post::query()
            ->with('media', 'category')
            ->whereType(Post::TYPE_MEDITATION)
            ->filterable([], ['vocabulary' => Term::VOCABULARY_MEDITATION_CATEGORIES])->paginate();

        return MeditationListResource::collection($meditations)->additional($this->addResources($request));
    }

    /**
     * @api {get} /api/meditations/{meditation:slug} 02. Одна сторінка медитації
     * @apiVersion 1.0.0
     * @apiName MeditationShow
     * @apiGroup Meditation
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "data": {
     *             "id": "9e77d602-7378-4a69-ba5c-6d7a5e6b5c5f",
     *             "entity": "breathing",
     *             "name": "Глибоке дихання",
     *             "slug": "hlyboke-dykhannia",
     *             "teaser": "Спокійне і повільне дихання для зняття напруги та відновлення енергії.",
     *             "body": "Вітаємо вас на початку шляху до глибокого спокою та внутрішньої рівноваги! Це дихання створене для тих, хто прагне знайти гармонію через прості практики. Займіть зручну позу, сидячи або лежачи, і розслабтеся. Зосередьтеся на своєму диханні. Повільно вдихайте, наповнюючи легеньки, і дозвольте повітрю природно вийти з вашого тіла. Уявіть, як з кожним вдихом ви наповнюєте себе новою енергією, а з кожним видихом — звільняєтеся від зайвих думок і напруги. Ваше тіло стає легким, а розум — спокійним. Дозвольте цій практиці стати частиною вашого щоденного життя, адже саме через регулярність ви зможете досягти глибшої концентрації та зберігати спокій у будь-яких ситуаціях.",
     *             "is_free": true,
     *             "states": {
     *                 "is_favorite": false,
     *                 "commentable": true
     *             },
     *             "image": {
     *                 "id": "9e77d602-a1fb-4f4d-8f70-aa9fa9808c46",
     *                 "name": "Блог - верх",
     *                 "url": "http://spokiyno.test/storage/9e77d602-a1fb-4f4d-8f70-aa9fa9808c46/blog-verx.jpg",
     *                 "conversions": {
     *                     "thumb": {
     *                         "url": "http://spokiyno.test/storage/9e77d602-a1fb-4f4d-8f70-aa9fa9808c46/conversions/blog-verx-thumb.jpg"
     *                     },
     *                 },
     *             },
     *         },
     *         "crumbs": []
     *     }
     */
    public function show(Request $request, string $slug)
    {
        $meditation = Post::query()
            ->with('media','category')
            ->where('slug', $slug)
            ->firstOrFail();

        return MeditationShowResource::make($meditation)->additional([
//            'seo' => SeoResource::make($meditation),
//            'crumbs' => $meditation->getBreadcrumbs(),
//            'sblocks' => \Block::getBlocksResource(\Domain::getOpt('posts.sblocks', []), true),
        ]);
    }

    /**
     * @apiPrivate
     * @api {get} /api/meditations/categories 03. Список категорій
     * @apiVersion 1.0.0
     * @apiName MeditationCategories
     * @apiGroup Meditation
     *
     */
    public function categories(Request $request)
    {
        $terms = Term::query()
            ->with('media')
            ->whereVocabulary(Term::VOCABULARY_MEDITATION_CATEGORIES)
            ->filterable()->get();

        return MeditationCategoryListResource::collection($terms);
    }

    /**
     * @api {get} /api/meditations/categories/{category:slug} 04. Одна категорія
     * @apiVersion 1.0.0
     * @apiName MeditationCategory
     * @apiGroup Meditation
     *
     */
    public function category(Request $request, Term $category)
    {
        /** @var Term $term */
        $term = $category->checkAllowed()->load('media');

        return MeditationCategoryShowResource::make($term)->additional([
//            'seo' => $term->relationLoaded('seo') ? SeoResource::make($term) : null
        ]);
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
                $add['categories'] = MeditationCategoryListResource::collection(Term::whereVocabulary(Term::VOCABULARY_MEDITATION_CATEGORIES)->with('translations')->get());
            }

//            if (in_array('info', $with)) {
//                $add['info'] = Item::getList(Item::TYPE_POSTS)->where('key', Post::TYPE_MEDITATION)->first();
//            }
        }

        return $add;
    }
}
