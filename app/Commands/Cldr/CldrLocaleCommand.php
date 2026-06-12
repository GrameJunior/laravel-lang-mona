<?php

declare(strict_types=1);

namespace LaravelLang\Mona\Commands\Cldr;

use Symfony\Component\Intl\Languages;

class CldrLocaleCommand extends CldrCommand
{
    protected string $directory = __DIR__ . "/../../../src/NativeLocaleNames/data";

    protected function perform(string $forCode, string $locale): ?string
    {
        return Languages::getName($locale, $forCode);
    }
}
