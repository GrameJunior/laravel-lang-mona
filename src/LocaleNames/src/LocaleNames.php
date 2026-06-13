<?php

declare(strict_types=1);

namespace LaravelLang\LocaleNames;

use LaravelLang\Support\Services\Cldr;

final class LocaleNames extends Cldr
{
    protected static function item(array|string $value, string $locale): LocaleData
    {
        return new LocaleData(
            code: $locale,
            name: $value,
        );
    }

    protected static function path(string $locale): string
    {
        return __DIR__ . '/../data/' . $locale . '.json';
    }
}
