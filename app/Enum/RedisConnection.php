<?php

namespace App\Enum;

enum RedisConnection
{
    case DEFAULT;
    case CACHE;
    case QUEUE;
}
