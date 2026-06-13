<?php

declare(strict_types=1);

namespace LaravelLang\CountryNames;

use LaravelLang\Support\Services\Cldr;

final class CountryNames extends Cldr
{
    protected static function item(array|string $value, string $locale): CountryData
    {
        return new CountryData(...$value);
    }

    protected static function path(string $locale): string
    {
        return __DIR__ . '/../data/' . $locale . '.json';
    }
}
