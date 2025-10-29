<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Gate;
//use Ka4ivan\Sluggable\Models\Traits\HasSlugs;
use App\Models\{Traits\HasDatetimeFormatterTz,
    Traits\HasNavigable,
    Traits\HasSlugTrait,
    Traits\HasStaticLists,
    Traits\InteractsWithMedia};
//    Traits\TermTokens,
//    Translations\Translatable};
use Fomvasss\MediaLibraryExtension\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Term extends \Fomvasss\SimpleTaxonomy\Models\Term implements HasMedia
{
    use HasSlugTrait,
//        Translatable,
        InteractsWithMedia,
//        HasSeo,
//        HasDomain,
        HasDatetimeFormatterTz,
        HasStaticLists,
        HasNavigable,
//        TermTokens,
        HasUuids;

    const VOCABULARY_ARTICLE_CATEGORIES = 'article_categories';
    const VOCABULARY_YOGA_CATEGORIES = 'yoga_categories';
    const VOCABULARY_SOUND_CATEGORIES = 'sound_categories';
    const VOCABULARY_MEDITATION_CATEGORIES = 'meditation_categories';
    const VOCABULARY_BREATHING_CATEGORIES = 'breathing_categories';
    const VOCABULARY_PROGRAMS = 'programs';

    protected $attributes = [
        'weight' => 10000,
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    protected array $mediaSingleCollections = ['image', 'logo'];

    public $translatedAttributes = ['name', 'body', 'intro_text', 'fields'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    /**
     * Отримуємо публікації за термами
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function postsByTerms()
    {
        return $this->morphedByMany(Post::class, 'termable', 'termables', 'term_id', 'termable_id');
    }

    public function checkAllowed()
    {
//        if ($this->status !== self::STATUS_PUBLISHED) {
//            abort(404);
//        }

        return $this;
    }

    /**
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @return array
     */
    public static function vocabulariesList(string $columnKey = null, string $indexKey = null): array
    {
        $records = [
            [
                'slug' => self::VOCABULARY_ARTICLE_CATEGORIES,
                'name' => 'Категорії статей',
                'has_hierarchy' => false,
                'fa_icon' => 'fa-file-picture-o',
                'permissions' => ['update' => Gate::check('post.update'), 'view' => Gate::check('post.view'), 'create' => Gate::check('post.create'), 'delete' => Gate::check('post.delete')],
            ],

            [
                'slug' => self::VOCABULARY_YOGA_CATEGORIES,
                'name' => 'Категорії йоги',
                'has_hierarchy' => false,
                'fa_icon' => 'fa-file-picture-o',
                'permissions' => ['update' => Gate::check('post.update'), 'view' => Gate::check('post.view'), 'create' => Gate::check('post.create'), 'delete' => Gate::check('post.delete')],
            ],

            [
                'slug' => self::VOCABULARY_SOUND_CATEGORIES,
                'name' => 'Категорії звуків',
                'has_hierarchy' => false,
                'fa_icon' => 'fa-file-picture-o',
                'permissions' => ['update' => Gate::check('post.update'), 'view' => Gate::check('post.view'), 'create' => Gate::check('post.create'), 'delete' => Gate::check('post.delete')],
            ],

            [
                'slug' => self::VOCABULARY_MEDITATION_CATEGORIES,
                'name' => 'Категорії медитацій',
                'has_hierarchy' => false,
                'fa_icon' => 'fa-file-picture-o',
                'permissions' => ['update' => Gate::check('post.update'), 'view' => Gate::check('post.view'), 'create' => Gate::check('post.create'), 'delete' => Gate::check('post.delete')],
            ],

            [
                'slug' => self::VOCABULARY_BREATHING_CATEGORIES,
                'name' => 'Категорії дихання',
                'has_hierarchy' => false,
                'fa_icon' => 'fa-file-picture-o',
                'permissions' => ['update' => Gate::check('post.update'), 'view' => Gate::check('post.view'), 'create' => Gate::check('post.create'), 'delete' => Gate::check('post.delete')],
            ],

            [
                'slug' => self::VOCABULARY_PROGRAMS,
                'name' => 'Програми',
                'has_hierarchy' => false,
                'fa_icon' => 'fa-file-picture-o',
                'permissions' => ['update' => Gate::check('post.update'), 'view' => Gate::check('post.view'), 'create' => Gate::check('post.create'), 'delete' => Gate::check('post.delete')],
            ],
        ];

        return self::staticListBuild($records, $columnKey, $indexKey);
    }

    /**
     * @param string $column
     * @return array
     */
    public function getVocabulary(string $column = 'name'): array
    {
        return self::vocabulariesList($column, 'slug')[$this->vocabulary] ?? [];
    }

    public function getCategoryPathStr( $delimiter = '>'): string
    {
        $res = '';
        foreach ($this->ancestors as $item) {
            $res .= $item->name . $delimiter;
        }

        $res .= $this->name ?? '';

        return  $res;
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::FillMax, 100, 100)
            ->format('webp');

        if ($media->model->vocabulary === self::VOCABULARY_PROGRAMS) {
            $this->addMediaConversion('page')
                //->fit(Fit::FillMax, 300, 300)
                ->format('webp');
        }
    }

    /**
     * @param string|null $group
     * @return array
     */
//    public function getSeoTags(?string $group = null): array
//    {
//        \StrToken::setEntities(['term' => $this]);
//
//        $patterns = \Variable::getArray("seo.patterns.{$this->vocabulary}", [], \Domain::getGroup());
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

    public function scopeFilterable(Builder $builder, array $params = [], array $default = [])
    {
        $f = ($params ?: request()->all()) + $default;
        $locale = Arr::get($f, 'locale', \app()->getLocale());

        $search = !empty($f['q']) ? mb_strtolower($f['q']) : null;
        $builder->when($search, fn($q) => $q->where("index->q_{$locale}", 'LIKE', "%{$search}%"));

        $builder->when(Arr::get($f, 'parent_id') === 0 || Arr::get($f, 'parent_id') === '0', fn($b) => $b->whereIsRoot());
        $builder->when($val = Arr::get($f, 'parent_id'), fn($b) => $b->whereParentId($val));

        $builder->when($val = Arr::get($f, 'ids') ?? Arr::get($f, 'id'), fn ($q) => $q->whereIn('id', Arr::wrap($val)));
        $builder->when($val = Arr::get($f, 'limit'), fn ($q) => $q->limit($val));
    }
}
