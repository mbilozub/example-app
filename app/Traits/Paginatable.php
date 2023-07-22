<?php

namespace App\Traits;

trait Paginatable
{
    protected function getPaginationLimit(string $key = '', bool $ignoreLimit = false): int
    {
        // Get limit from ?take - parameter
        $fromRequest = request('take');

        if ($fromRequest) {
            $max = config('pagination.default.max');

            return ($fromRequest <= $max || $ignoreLimit) ? $fromRequest : $max;
        }

        $limit = config("pagination.{$key}");

        if (!$limit) {
            return config('pagination.default.average');
        }

        return $limit;
    }
}
