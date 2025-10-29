<?php

namespace App\Models;

use Fomvasss\Blocks\Models\HasBlocks;
use App\Models\Traits\HasDatetimeFormatterTz;
use App\Models\Traits\HasSlugTrait;
use App\Models\Traits\HasStaticLists;
use App\Models\Traits\InteractsWithMedia;
use Fomvasss\MediaLibraryExtension\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Page extends Model implements HasMedia
{
    use HasFactory,
        HasStaticLists,
        InteractsWithMedia,
        HasSlugTrait,
        HasUuids,
        HasDatetimeFormatterTz,
        HasBlocks;

    const STATUS_PUBLISHED = 'published';
    const STATUS_HIDDEN = 'hidden';

    protected $guarded = ['id'];

    protected $attributes = [
        'status' => self::STATUS_PUBLISHED,
    ];

    protected $casts = [
        'locales' => 'array',
    ];

    public $translatedAttributes = ['name', 'body'];

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
     * @param Builder $builder
     * @return Builder
     */
    public function scopeByAllowed(Builder $builder)
    {
        return $builder
            ->where('status', self::STATUS_PUBLISHED)
            ;
    }

    /**
     * @return bool
     */
    public function isAllowed(): bool
    {
        if ($this->status !== self::STATUS_PUBLISHED) {
            return false;
        }

        return true;
    }

    /**
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @return array
     */
    public static function templatesList(string $columnKey = null, string $indexKey = null): array
    {
        $records = [
            [
                'key' => 'default',
                'name' => 'По замовчуванню',
                'blocks' => [],
            ],
            [
                'key' => 'home',
                'name' => 'Головна',
                'blocks' => ['contacts',],
            ],
        ];

        return self::staticListBuild($records, $columnKey, $indexKey);
    }

    /**
     * @return string
     */
    public function getUrlClient($params = []): string
    {
        return route('pages.show', array_merge([$this], Arr::wrap($params)));
    }
}
