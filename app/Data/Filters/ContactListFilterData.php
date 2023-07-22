<?php

namespace App\Data\Filters;

use App\Enum\SortType;
use Spatie\LaravelData\Data;

class ContactListFilterData extends Data
{
    public function __construct(
        public ?string $search,
        public ?SortType $order = SortType::desc,
    ) {
    }
}
