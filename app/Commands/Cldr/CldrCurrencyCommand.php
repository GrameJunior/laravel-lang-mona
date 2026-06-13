<?php

declare(strict_types=1);

namespace LaravelLang\Mona\Commands\Cldr;

final class CldrCurrencyCommand extends CldrCommand
{
    protected string $targetDirectory = __DIR__ . '/../../../src/CurrencyNames/data';

    protected function perform(string $forCode, string $locale): array|string|null
    {
        return [
            'code'      => 'ZAR',
            'numeric'   => 710,
            'native'    => 'South African Rand',
            'localized' => 'южноафриканский рэнд',
        ];
    }
}
