<?php

namespace App\Models;

use App\Models\Traits\HasStaticLists;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Variable extends \Fomvasss\Variable\Models\Variable
{
    use HasUuids,
        HasStaticLists;

    public static function sectionsList(string $columnKey = null, string $indexKey = null, array $options = []): array
    {
        $records = [
            [
                'title' => 'Списки',
                'key' => 'items',
                'fa_icon' => 'fas fa-list',
                'perm' => 'settings.content',
            ],
        ];

        return self::staticListBuild($records, $columnKey, $indexKey, $options);
    }


    /**
     * @param string|null $columnKey
     * @param string|null $indexKey
     * @return array
     */
    public static function tokensList(string $columnKey = null, string $indexKey = null): array
    {
        $records = [
            [
                'key' => '[var:contact:phone]',
                'name' => 'Contact Phone',
            ],
            [
                'key' => '[var:contact:email]',
                'name' => 'Contact Email',
            ],
        ];

        return self::staticListBuild($records, $columnKey, $indexKey);
    }

    public static function strTokensValues(): array
    {
        return [
            'name' => config('app.name'),
        ];
    }
}
