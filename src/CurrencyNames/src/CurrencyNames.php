<?php

declare(strict_types=1);

namespace LaravelLang\CurrencyNames;

use LaravelLang\Support\Services\Cldr;

final class CurrencyNames extends Cldr
{
    protected static function item(array|string $value, string $locale): CurrencyData
    {
        return new CurrencyData(...$value);
    }

    protected static function path(string $locale): string
    {
        return __DIR__ . '/../data/' . $locale . '.json';
    }
}
