<?php

namespace App\Models;

use App\Models\Traits\HasDatetimeFormatterTz;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class Usertracker extends Model
{
    use HasUuids,
        HasFactory,
        HasDatetimeFormatterTz;

    const TYPE_TOTAL = 'total';
    const PERIOD_DAY = 'day';

    protected $guarded = ['id'];

    protected $casts = [
        'data' => 'array',
        'fixed_at' => 'date',
    ];

    protected $attributes = [
        'period' => self::PERIOD_DAY,
        'type' => self::TYPE_TOTAL,
    ];

    protected static function booted(): void
    {
        static::saving(function (self $model) {
            $model->setAttribute('fixed_at', now()->format('Y-m-d'));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function emotions()
    {
        return $this->belongsToMany(Item::class, 'usertracker_emotion', 'usertracker_id', 'emotion_id')
            ->withPivot('updated_at');
    }

    /**
     * Отримуємо дані за ключем у Data
     *
     * @param string $key
     * @return string|int|null
     */
    public function getData(string $key, $default = null): string|int|null
    {
        return Arr::get($this->data, $key, $default);
    }

    public function getEmotionsObj()
    {
        $ids = $this->emotions->pluck('id')->toArray();

        return Item::getList(Item::TYPE_EMOTIONS)->whereIn('id', $ids)->values();
    }

    public function scopeFilterable(Builder $builder, array $attrs = [], array $default = [])
    {
        $attrs = ($attrs ?: request()->all()) ?: $default;

        $appTZ = config('app.timezone');
        if ($val = Arr::get($attrs, 'created_at_from')) {
            $builder->whereDate('created_at', '>=', Carbon::parse($val)->startOfDay()->setTimezone($appTZ));
        }
        if ($val = Arr::get($attrs, 'created_at_to')) {
            $builder->whereDate('created_at', '<=', Carbon::parse($val)->endOfDay()->setTimezone($appTZ));
        }

        $builder->when($val = Arr::get($attrs, 'limit'), fn($q) => $q->limit($val));
    }
}
