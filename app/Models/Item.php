<?php

namespace App\Models;

use App\Models\Traits\HasDomain;
use App\Models\Traits\HasStaticLists;
use App\Models\Traits\InteractsWithMedia;
use App\Models\Translations\Translatable;
use Fomvasss\MediaLibraryExtension\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Списки: джерела, статуси, типи і т.д.
 */
class Item extends Model implements HasMedia
{
    use HasFactory,
        HasUuids,
        HasStaticLists,
        InteractsWithMedia;

    const TYPE_POSTS = 'post_types';

    const TYPE_EMOTIONS = 'emotions';

    const TYPE_MOODS = 'moods';

    const TYPE_FORESIGHT = 'foresight';

    const TYPE_HELLO = 'hello';

    const TYPE_COLORS = 'profile_colors';


    protected $guarded = ['id'];

    public $translatedAttributes = ['name', 'desc', 'title'];

    protected $attributes = [
        'weight' => 10000,
    ];

    protected $casts = [
        'is_guarded' => 'boolean'
    ];

    protected array $mediaSingleCollections = [
        'image',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('weight', function (Builder $builder) {
            $builder->orderBy('weight');
        });

        static::saving(function (self $item) {
            $cacheKey = md5("items_{$item->type}");
            Cache::forget($cacheKey);
        });

        static::creating(function (self $item) {
            if (empty($item->key)) {
                $key = Str::slug(Str::limit($item->name ?: $item->desc ?: '', 20, ''), '_');
                $item->setAttribute('key', $key);
            }
        });
    }

    public static function typesList(string $columnKey = null, string $indexKey = null, array $options = []): array
    {
        $records = [
//            [
//                'title' => 'Типи матеріалів',
//                'key' => self::TYPE_POSTS,
//                'settings' => [
//                    'sortable' => true,
//                    'create' => false,
//                ],
//                'fields' => [
//                    ['name' => 'key', 'label' => 'Ключ', 'type' => 'text', 'readonly' => true,],
//                    ['name' => 'name', 'label' => 'Назва', 'type' => 'text', 'editable_type' => 'xEditable', 'required' => true,],
//                    ['name' => 'title', 'label' => 'Підзаголовок', 'type' => 'text', 'editable_type' => 'xEditable',  'required' => true,],
//                    ['name' => 'image', 'label' => 'Зображення', 'type' => 'mediaImage'],
//                    ['name' => 'desc', 'label' => 'Опис', 'type' => 'textarea', 'rows' => 8],
//                ],
//            ],
            [
                'title' => 'Емоції',
                'key' => self::TYPE_EMOTIONS,
                'settings' => [
                    'sortable' => true,
                ],
                'fields' => [
                    ['name' => 'key', 'label' => 'Ключ', 'type' => 'text', 'readonly' => true,],
                    ['name' => 'name', 'label' => 'Назва', 'type' => 'text', 'editable_type' => 'xEditable',],
                    ['name' => 'color', 'label' => 'Колір', 'type' => 'colorpicker', 'editable_type' => 'colorpicker'],
                ],
            ],
//            [
//                'title' => 'Настрій',
//                'key' => self::TYPE_MOODS,
//                'settings' => [
//                    'sortable' => true,
//                ],
//                'fields' => [
//                    ['name' => 'key', 'label' => 'Ключ', 'type' => 'text', 'readonly' => true,],
//                    ['name' => 'name', 'label' => 'Назва', 'type' => 'text', 'editable_type' => 'xEditable',],
//                    ['name' => 'icon', 'label' => 'Емодзі', 'type' => 'text', 'editable_type' => 'xEditable',],
//                    //['name' => 'image', 'label' => 'Зображення', 'type' => 'lfmImage'],
//                ],
//            ],
//            [
//                'title' => 'Передбачення',
//                'key' => self::TYPE_FORESIGHT,
//                'settings' => [
//                    'sortable' => false,
//                ],
//                'fields' => [
//                    //['name' => 'key', 'label' => 'Ключ', 'type' => 'text', 'readonly' => true,],
//                    //['name' => 'name', 'label' => 'Назва', 'type' => 'text',],
//                    ['name' => 'desc', 'label' => 'Опис', 'type' => 'textarea', 'editable_type' => 'xEditable',],
//                ],
//            ],
//            [
//                'title' => 'Вітальні фрази',
//                'key' => self::TYPE_HELLO,
//                'settings' => [
//                    'sortable' => false,
//                ],
//                'fields' => [
//                    ['name' => 'key', 'label' => 'Ключ', 'type' => 'text', 'readonly' => true,],
//                    ['name' => 'desc', 'label' => 'Фраза', 'type' => 'text', 'editable_type' => 'xEditable',],
//                    ['name' => 'bg', 'label' => 'Пора доби', 'type' => 'select2', 'options' => ['night' => 'Night', 'morning' => 'Morning', 'day' => 'Day', 'evening' => 'Evening'],],
//                    //['name' => 'desc', 'label' => 'Опис', 'type' => 'textarea', 'editable_type' => 'xEditable',],
//                ],
//            ],
//            [
//                'title' => 'Кольори профілю',
//                'key' => self::TYPE_COLORS,
//                'settings' => [
//                    'sortable' => false,
//                ],
//                'fields' => [
//                    //['name' => 'key', 'label' => 'Ключ', 'type' => 'text', 'readonly' => true,],
//                    ['name' => 'name', 'label' => 'Назва', 'type' => 'text', 'editable_type' => 'xEditable',],
//                    ['name' => 'color', 'label' => 'Колір', 'type' => 'colorpicker', 'editable_type' => 'colorpicker'],
//                ],
//            ],
        ];

        return self::staticListBuild($records, $columnKey, $indexKey, $options);
    }

    public static function settingsList(string $columnKey = null, string $indexKey = null, array $options = []): array
    {
        $records = [

        ];

        return self::staticListBuild($records, $columnKey, $indexKey, $options);
    }

    /**
     * @param string $type
     * @param array $fields
     * @return Collection
     */
    public static function getList(string $type, array $fields = []): Collection
    {
        $cacheKey = md5("items_{$type}");

        $items = Cache::remember($cacheKey, 60 * 60 * 24, function () use ($type) {
            return self::query()
                ->where('type', $type)
                ->get();
        });

        // Якщо поля не передані — беремо їх із конфігурації типу
        $fields = $fields ?: array_column((new self)->typesList('fields', 'key')[$type] ?? [], 'name');

        // Додаємо 'id', бо потрібно для getEmotionsObj у моделі Usertracker
        $fields = array_merge($fields, ['id']);

        return $items->map(fn($item) => $item->only($fields));
    }

    /**
     * @param string $type
     * @return Builder|Model|object|null
     */
    public static function getRandom(string $type)
    {
        return Item::query()->where('type', $type)->inRandomOrder()->first();
    }

    public static function getSettingsValue(string $type, string $setting, string $default = null): ?string
    {
        return \Variable::getArray("lists.settings.$type.$setting", $default);
    }

    /** Доступні значення для полів
     *
     * TODO Some refactor this method. Maybe remove or move in another place
     */
    private function getExistingFields(): array
    {
        return [
            'key',
            'name',
            'desc',
            'icon',
            'color',
            'bg',
        ];
    }
}
