<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory, HasUuids;

    const DEFAULT_ROLE_GUEST = 'guest';
    const DEFAULT_ROLE_CLIENT = 'client';
    const DEFAULT_ROLE_ADMIN = 'admin';

    protected $casts = [
        'modules' => 'array',
    ];
}
