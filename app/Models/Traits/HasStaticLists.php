<?php

namespace App\Models\Traits;

use Illuminate\Support\Arr;

trait HasStaticLists
{
    protected static function staticListBuild(
        array $records = [],
        string $columnKey = null,
        string $indexKey = null,
        array $options = [],
    ): array {

        if ($indexKey && ($only = $options['only'] ?? [])) {
            $only = \is_array($only) ? $only : [$only];
//            $records = array_filter($records, function ($record) use ($indexKey, $only) {
//                return in_array($record[$indexKey], $only);
//            });

            // фільтрувати та посортувати по $only
            $res = [];
            foreach ($only as $key) {
                foreach ($records as $record) {
                    if ($record[$indexKey] === $key) {
                        $res[] = $record;
                        break;
                    }
                }
            }
            $records = $res;
        }

        // виключити without
        if ($without = $options['except'] ?? $options['without'] ?? []) {
            $without = Arr::wrap($without);
            $records = array_filter($records, function ($record) use ($indexKey, $without) {
                return !in_array($record[$indexKey], $without);
            });
        }

        if ($indexKey && $columnKey) {
            if ($columnKey === '*' || $columnKey === ['*']) {
                return array_combine(array_column($records, $indexKey), $records);
            }

            return array_column($records, $columnKey, $indexKey);
        }

        elseif ($columnKey) {
            return array_column($records, $columnKey);
        }

        return $records;
    }
}
