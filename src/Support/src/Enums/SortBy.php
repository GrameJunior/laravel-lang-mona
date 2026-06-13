<?php

declare(strict_types=1);

namespace LaravelLang\Support\Enums;

enum SortBy: int
{
    case None  = 0;
    case Key   = 1;
    case Value = 2;
}
