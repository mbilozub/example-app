<?php

namespace App\Enum;

enum CacheStore
{
    case ARRAY;
    case REDIS;
    case OCTANE;
}
