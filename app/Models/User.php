<?php

namespace App\Models;

use App\Models\Traits\HasDatetimeFormatterTz;
use App\Models\Traits\HasStaticLists;
use Fomvasss\MediaLibraryExtension\HasMedia\InteractsWithMedia;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Fomvasss\MediaLibraryExtension\HasMedia\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasFactory,
        Notifiable,
        HasUuids,
        HasStaticLists,
        HasDatetimeFormatterTz,
        InteractsWithMedia,
        HasRoles;

    const STATUS_ACTIVE = 'active';
    const STATUS_BLOCKED = 'blocked';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activity_at' => 'datetime',
            'birthday' => 'date',
            'fields' => 'array',
        ];
    }

    /**
     * @var string[]
     */
    protected $attributes = [
        'status' => self::STATUS_ACTIVE,
    ];

    /**
     * Часова зона поточного користувача.
     *
     * @return string
     */
    public function getTimezone(): string
    {
        return $this->timezone ?: config('app.timezone_client') ?: config('app.timezone');
    }

    /**
     * @param $value
     */
    public function setPhoneAttribute($value)
    {
        if ($value) {
            $this->attributes['phone'] = preg_replace('/[^0-9]/', '', $value);
        } else {
            $this->attributes['phone'] = null;
        }
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        // TODO: or media or socialite
        return $this->relationLoaded('media') && $this->hasMedia('avatar')
            ? $this->getFirstMediaUrl('avatar')
            : \Avatar::create($this->fullname ?: $this->email ?: $this->id)->setDimension(150)->setFontSize(56)->toBase64();
    }

    /**
     * @return bool
     */
    public function isOnline(): bool
    {
        if ($this->activity_at) {
            return $this->activity_at->addMinute(3)->isFuture();
        }

        return false;
    }

    /**
     * Список статусів.
     *
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @return array
     */
    public static function statusesList(string $columnKey = null, string $indexKey = null): array
    {
        $records = [
            [
                'key' => self::STATUS_ACTIVE,
                'name' => 'Активний',
            ],
            [
                'key' => self::STATUS_BLOCKED,
                'name' => 'Заблокований',
            ],
        ];

        return self::staticListBuild($records, $columnKey, $indexKey);
    }

    /**
     * @return string
     */
    public function getStatus($column = 'name'): string|array|null
    {
        return self::statusesList($column, 'key')[$this->status] ?? '';
    }

    /**
     * Список ролей.
     *
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @param array $options
     * @return array
     */
    public static function rolesList(string $columnKey = null, string $indexKey = null, array $options = []): array
    {
        $records = Role::select('name')->get()->map(fn($r) => ['name' => $r->name, 'key' => $r->name])->toArray();

        return self::staticListBuild($records, $columnKey, $indexKey, $options);
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function scopeByNotDev(Builder $builder)
    {
        $emails = config('auth.dev_user');

        if ($email = auth()->user()?->email) {
            $emails = array_unset_value($emails, $email);
        }

        $builder->when($emails, fn($b) => $b->where(fn($b2) => $b2->whereNotIn('email', $emails)->orWhereNull('email')));
    }

    /**
     * @return Attribute
     */
    protected function fullname(): Attribute
    {
        return Attribute::make(
            get: fn () => trim($this->lastname . ' ' . $this->name),
        );
    }

    public static function passwordGenerate(): string
    {
        return Str::random(8);
    }

    /**
     * @param $key
     * @param null $default
     * @return array|\ArrayAccess|mixed
     */
    public function getAdded($key, $default = null)
    {
        return Arr::get($this->added ?? [], $key, $default);
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('avatar')
            ->fit(Fit::FillMax, 300, 300)
            ->format('webp')
            ->performOnCollections('avatar');
    }

    /**
     * @param Builder $builder
     * @param array $attrs
     * @param array $default
     */
    public function scopeFilterable(Builder $builder, array $attrs = [], array $default = [])
    {
        $attrs = ($attrs ?: request()->all()) + $default;

        $builder->when($val = Arr::get($attrs, 'q'), fn($b) => $b->where(fn($q2) => $q2->whereAny(['name', 'lastname', 'email', 'phone'], 'LIKE', "%$val%")));

        $builder->when($val = filter_explode(Arr::get($attrs, 'ids')), fn($q) => $q->whereIn('id', $val));
        $builder->when($val = filter_explode(Arr::get($attrs, 'id')), fn($q) => $q->whereIn('id', $val));
        $builder->when($val = Arr::get($attrs, 'name'), fn($b) => $b->where('name', 'LIKE', "%$val%"));
        $builder->when($val = Arr::get($attrs, 'email'), fn($b) => $b->where('email', 'LIKE', "%$val%"));
        $builder->when($val = Arr::get($attrs, 'status'), fn($b) => $b->whereIn('status', Arr::wrap($val)));
        $builder->when($val = Arr::get($attrs, 'role'), fn($b) => $b->whereHas('roles', fn($r) => $r->whereIn('name', Arr::wrap($val))));


        $appTZ = config('app.timezone');

        if ($val = Arr::get($attrs, 'created_at_from')) {
            $builder->whereDate('created_at', '>=', Carbon::parse($val)->startOfDay()->setTimezone($appTZ));
        }
        if ($val = Arr::get($attrs, 'created_at_to')) {
            $builder->whereDate('created_at', '<=', Carbon::parse($val)->endOfDay()->setTimezone($appTZ));
        }

        if ($val = Arr::get($attrs, 'activity')) {
            $builder->when($val === 'has', fn($b) => $b->whereNotNull('activity_at'), fn($b) => $b->whereNull('activity_at'));
        }

        if ($val = Arr::get($attrs, 'activity_at_from')) {
            $builder->whereDate('activity_at', '>=', \Carbon\Carbon::parse($val)->startOfDay()->setTimezone($appTZ));
        }
        if ($val = Arr::get($attrs, 'activity_at_to')) {
            $builder->whereDate('activity_at', '<=', Carbon::parse($val)->endOfDay()->setTimezone($appTZ));
        }

        // Sort
        if ($sort = Arr::get($attrs, 'sort')) {
            $order = Arr::get($attrs, 'order');
            $order = in_array($order, ['asc', 'desc']) ? $order : 'asc';
            if (in_array($sort, ['name', 'email', 'phone', 'created_at', 'activity_at', 'status', 'role',])) {
                $builder->orderBy($sort, $order);
            } elseif ($sort === 'fullname') {
                $builder->orderByRaw("CONCAT_WS(' ', `lastname`, `name`, `middlename`) {$order}");
            } else {
                $builder->latest();
            }
        } else {
            $builder->latest();
        }

        // Limit
        $val = Arr::get($attrs, 'limit') ?: Arr::get($default, 'limit');
        $builder->when($val, fn($b) => $b->limit($val));
    }
}
