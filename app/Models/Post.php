<?php

namespace App\Models;

use App\Http\Client\Api\Resources\Article\ArticleListResource;
use App\Http\Client\Api\Resources\Meditation\MeditationListResource;
use App\Models\Traits\HasDatetimeFormatterTz;
use App\Models\Traits\HasNavigable;
use App\Models\Traits\HasSlugTrait;
use App\Models\Traits\HasStaticLists;
use App\Models\Traits\HasTaxonomies;
//use App\Models\Traits\HasSeo;
use App\Models\Traits\InteractsWithMedia;
//use App\Models\Traits\PostTokens;
//use App\Models\Translations\Translatable;
use Fomvasss\MediaLibraryExtension\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
//use Ka4ivan\Sluggable\Models\Traits\HasSlugs;

class Post extends Model implements HasMedia
{
    use HasFactory,
//        Translatable,
        HasDatetimeFormatterTz,
        HasStaticLists,
        HasTaxonomies,
        InteractsWithMedia,
        HasUuids,
        HasSlugTrait,
        HasNavigable;
//        HasAddFields,
//        PostTokens,
//        HasDomain;

    const STATUS_PUBLISHED = 'published';
    const STATUS_HIDDEN = 'hidden';

    const TYPE_ARTICLE = 'article';
//    const TYPE_BREATHING = 'breathing';
    const TYPE_MEDITATION = 'meditation';
//    const TYPE_SOUND = 'sound';
//    const TYPE_YOGA = 'yoga';

    protected $guarded = ['id'];

    protected $casts = [
        'index' => 'array',
        'locales' => 'array',
        'published_at' => 'datetime',
        'is_free' => 'boolean',
    ];

    protected $attributes = [
        'type' => self::TYPE_ARTICLE,
        'status' => self::STATUS_PUBLISHED,
        'is_free' => true,
    ];

    protected array $mediaSingleCollections = [
        'image',          // Основне фото - для каталогу/списку
        'audio',          // Звуки
    ];

    public $translatedAttributes = ['name', 'body', 'teaser'];

    public static function booted()
    {
        self::saving(function (self $post) {
            if ($post->type === self::TYPE_ARTICLE) {
                $post->duration = calculate_reading_time($post->body, 1500, 'json') * 60;
            }
        });

//        self::deleted(function (self $post) {
//            if ($post->sort !== 0) {
//                MakePatternSortedPosts::dispatch();
//            }
//        });
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

//    public function video(): \Illuminate\Database\Eloquent\Relations\MorphOne
//    {
//        return $this->morphOne(Video::class, 'model')->whereType(Video::TYPE_DEFAULT);
//    }
//
//    public function videos(): \Illuminate\Database\Eloquent\Relations\MorphMany
//    {
//        return $this->morphMany(Video::class, 'model')->whereType(Video::TYPE_DEFAULT)->orderBy('weight')->oldest();
//    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->term('category_id', 'id');
    }

    public function categories(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->terms();
    }

    /**
     * @return void
     */
    public function setSequences(): void
    {
        if ($this->type === 'course') {
            $i = 1;
            foreach ($this->videos as $video) {
                $video->setAttribute('sequence', $i++)->saveQuietly();
            }
        }
    }

    /**
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @return array
     */
    public static function statusesList(string $columnKey = null, string $indexKey = null): array
    {
        $records = [
            [
                'key' => self::STATUS_PUBLISHED,
                'name' => 'Опубліковано',
            ],
            [
                'key' => self::STATUS_HIDDEN,
                'name' => 'Приховано',
            ],
        ];

        return self::staticListBuild($records, $columnKey, $indexKey);
    }

    /**
     * @param string $column
     * @return string|array|null
     */
    public function getStatus(string $column = 'name'): string|array|null
    {
        return self::statusesList($column, 'key')[$this->status] ?? null;
    }

    /**
     * Фільтруємо по типу
     *
     * @param $query
     * @param string $type
     * @return Builder
     */
    public function scopeOfType($query, string $type): Builder
    {
        return $query->whereType($type);
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeByAllowed(Builder $builder): Builder
    {
        return $builder
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('status', self::STATUS_PUBLISHED);
    }

    /**
     * Отримуємо випадковий матеріал за типом
     *
     * @param string $type
     * @return self|null
     */
    public static function randomByType(string $type): ?self
    {
        return self::ofType($type)->inRandomOrder()->select('id')->first();
    }

    /**
     * Сортуємо по типам
     *
     * @param Collection $posts
     * @param array $types
     * @return \Illuminate\Support\Collection
     */
    public static function orderedListByTypes(Collection $posts, array $types)
    {
        $list = collect();

        foreach ($types as $type) {
            $post = $posts->firstWhere('type', $type);

            if (!$post) {
                continue;
            }

            $resourceClass = match ($type) {
                self::TYPE_MEDITATION => MeditationListResource::class,
//                self::TYPE_YOGA => YogaListResource::class,
//                self::TYPE_BREATHING => BreathingListResource::class,
                self::TYPE_ARTICLE => ArticleListResource::class,
//                self::TYPE_SOUND => SoundListResource::class,
                default => null,
            };

            if ($resourceClass) {
                $list->push($resourceClass::make($post));
            }
        }

        return $list;
    }

    /**
     * Для адмінпанелі!
     *
     * @return bool
     */
    public function isAllowed(): bool
    {
        if ($this->status !== self::STATUS_PUBLISHED) {
            return false;
        }

        if (is_null($this->published_at)) {
            return false;
        }

        if ($this->published_at->gt(now())) {
            return false;
        }

        return true;
    }

    /**
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @param array $options
     * @return array
     */
    public static function typesList(string $columnKey = null, string $indexKey = null, array $options = []): array
    {
        $records = [
            [
                'key' => self::TYPE_ARTICLE,
                'name' => 'Статті',
            ],
//            [
//                'key' => self::TYPE_BREATHING,
//                'name' => 'Дихання',
//            ],
//            [
//                'key' => self::TYPE_SOUND,
//                'name' => 'Звуки',
//            ],
//            [
//                'key' => self::TYPE_YOGA,
//                'name' => 'Йога',
//            ],
            [
                'key' => self::TYPE_MEDITATION,
                'name' => 'Медитація',
            ],
        ];

        return self::staticListBuild($records, $columnKey, $indexKey, $options);
    }

    /**
     * @return bool
     */
    public function isFavorite(): bool
    {
        return \Favorite::isFavorite($this);
    }

    /**
     * @return bool
     */
    public function isFree(): bool
    {
        return $this->is_free;
    }

    /**
     * Стани моделі для клієнта.
     *
     * @return array
     */
    public function getClientStates(): array
    {
        return [
//            'is_favorite' => $this->isFavorite(),
//            'is_check' => $this->isCheck(),
//            'is_check_plan_today' => $this->isCheckPlanToday(),
        ];
    }

    /**
     * Визначаємо, чи користувач переглянув статтю або 60%+ відео.
     *
     * @return bool
     */
//    public function isCheck(): bool
//    {
//        /** @var User|null $user */
//        $user = auth()->user();
//        if (is_null($user)) {
//            return false;
//        }
//
//        $watched = $user->watcheds
//            ->firstWhere(fn ($watch) =>
//                $watch->post_id === $this->id
//                && $watch->action === Watched::ACTION_VIEW
//            );
//
//        return match ($this->type) {
//            'article', 'meditation', 'sound' => (bool) $watched,
//            'breathing', 'yoga' =>
//                $watched && (round($watched->time, 1) >= (round($this->video?->duration * 0.6, 1))),
//            default => false,
//        };
//    }
//
//    public function isCheckPlanToday(): bool
//    {
//        /** @var User|null $user */
//        $user = auth()->user();
//        if (is_null($user)) {
//            return false;
//        }
//
//        $watched = $user->watcheds
//            ->firstWhere(fn ($watch) =>
//                $watch->post_id === $this->id
//                && $watch->action === Watched::ACTION_VIEW_TODAY
//                && $watch->created_at->isToday()
//            );
//
//        return match ($this->type) {
//            self::TYPE_ARTICLE, self::TYPE_MEDITATION, self::TYPE_SOUND => (bool) $watched,
//            self::TYPE_BREATHING, self::TYPE_YOGA =>
//                $watched && (round($watched->time, 1) >= (round($this->video?->duration * 0.6, 1))),
//            default => false,
//        };
//    }
//
    public function getTeaser(): string
    {
        return $this->teaser ?: Str::limit(strip_tags($this->body ?: ''), 1500, '');
    }
//
//    public function getSeoTags(): array
//    {
//        \StrToken::setEntities(['post' => $this]);
//
//        $patterns = \Variable::getArray('seo.patterns.post', [], \Domain::getGroup());
//
//        $res = $this->getRawSeoTags(\Domain::getLocale()) ?: $patterns;
//        $res['og_image'] = $this->getMyFirstMediaUrl('image', 'og_image');
//
//        foreach ($res as $key => $value) {
//            $value = $value ?: Arr::get($patterns, $key, '');
//            if ($value && is_string($value)) {
//                $res[$key] = \StrToken::setText($value)->replace();
//            }
//        }
//
//        return $res;
//    }

    public function getName(): string
    {
        return $this->name ?: '';
    }

    /**
     * Рекомендовані.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRecommends()
    {
        $recommends = self::with('media', 'category')
            ->where('category_id', $this->category?->id)
            ->where('id', '<>', $this->id)
            ->inRandomOrder()
            ->limit(8)
            ->get();

        return $recommends;
    }

    /**
     * @return array
     */
//    public function getBreadcrumbs(): array
//    {
//        $terms = [];
//        if ($this->category_id) {
//            $terms = Term::with('translations')->defaultOrder()->ancestorsAndSelf($this->category_id);
//        }
//
//        $res = [];
//        /** @var Term $term */
//        foreach ($terms as $term) {
//            $res[] = $term->only('id', 'slug', 'name') + ['model' => 'term'];
//        }
//
//        $res[] = $this->only('id', 'slug', 'name') + ['model' => 'post'];
//
//        return $res;
//    }

    public function scopeFilterable(Builder $builder, array $attrs = [], array $default = [])
    {
        $attrs = ($attrs ?: request()->all()) + $default;

        $attrs = self::prepeareFilterArray($attrs);
        $locale = Arr::get($attrs, 'locale', \app()->getLocale());

        $search = !empty($attrs['q']) ? mb_strtolower($attrs['q']) : null;
        $builder->when($search, fn($q) => $q->where("index->q_{$locale}", 'LIKE', "%{$search}%"));

        if ($val = Arr::get($attrs, 'status')) {
            $builder->when($val === 'published', fn($b) => $b->whereNotNull('published_at'), fn($b) => $b->whereNull('published_at'));
        }

        $appTZ = config('app.timezone');
        if ($val = Arr::get($attrs, 'published_at_from')) {
            $builder->whereDate('published_at', '>=', Carbon::parse($val)->startOfDay()->setTimezone($appTZ));
        }
        if ($val = Arr::get($attrs, 'published_at_to')) {
            $builder->whereDate('published_at', '<=', Carbon::parse($val)->endOfDay()->setTimezone($appTZ));
        }

        $builder->when($val = filter_explode(Arr::get($attrs, 'type')), fn($q) => $q->whereIn('type', $val));
        $builder->when($val = filter_explode(Arr::get($attrs, 'only')), fn($q) => $q->whereIn('id', $val));
        $builder->when($val = filter_explode(Arr::get($attrs, 'except')), fn($q) => $q->whereNotIn('id', $val));
        $builder->when($val = Arr::get($attrs, 'category'), fn($q) => $q->whereJsonContains('index->categories', $val));


        // Sort
        if ($sort = Arr::get($attrs, 'sort')) {
            $order = Arr::get($attrs, 'order');
            $order = in_array($order, ['asc', 'desc']) ? $order : 'asc';
            if (in_array($sort, ['created_at', 'published_at'])) {
                $builder->orderByRaw("CAST(`index`->'$.{$sort}' AS UNSIGNED) {$order}");
            } elseif (in_array($sort, ['name',])) {
                $builder->orderBy("index->{$sort}_{$locale}", "{$order}");
            } elseif ($sort === 'random') {
                $builder->inRandomOrder();
            } else {
                $builder->latest('published_at');
            }
        } else {
            $builder->latest('published_at');
        }

        $builder->when($val = Arr::get($attrs, 'limit'), fn($q) => $q->limit($val));
    }

    protected static function prepeareFilterArray(array $attrs = [])
    {
        $res = [];
        $res['locale'] = /*\Domain::getLocale() ?: */app()->getLocale();

        $terms = Term::query()
            ->whereVocabulary(Arr::get($attrs, 'vocabulary'))
            ->pluck('id', 'slug')
            ->toArray();

        foreach (Arr::only($attrs, ['categories', 'tags']) as $key => $val) {
            $val = is_string($val) ? explode(',', $val) : Arr::wrap($val);

            $res[$key] = Arr::get($attrs, '_by', 'slug') === 'slug'
                ? array_values(Arr::only($terms, array_filter($val)))
                : $val;
        }


        if ($val = Arr::get($attrs, 'category')) {
            $categoryId = Arr::get($attrs, '_by', 'slug') === 'slug'
                ? Arr::get($terms, $val)
                : $val;

            if ($categoryId) {
                $res['category'] = $categoryId;
            }
        }

        return array_merge($attrs, $res);
    }
}
