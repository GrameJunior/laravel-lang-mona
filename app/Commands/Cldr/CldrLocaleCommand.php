<?php

declare(strict_types=1);

namespace LaravelLang\Mona\Commands\Cldr;

use LaravelLang\LocaleNames\LocaleData;
use LaravelLang\Mona\Concerns\HasLanguages;
use RuntimeException;

use function sprintf;

final class CldrLocaleCommand extends CldrCommand
{
    use HasLanguages;

    protected string $targetDirectory = __DIR__ . '/../../../src/LocaleNames/data';

    protected function perform(string $forCode, string $locale): LocaleData
    {
        $forLanguages = $this->languages($forCode);
        $fallback     = $this->languages($this->fallback);

        $code = $this->localeCode($locale);
        $base = $this->baseCode($code);

        $name = $forLanguages[$code]
            ?? $forLanguages[$base]
            ?? $fallback[$code]
            ?? $fallback[$base]
            ?? throw new RuntimeException(
                sprintf('Unable to find CLDR locale name for [%s] in [%s].', $locale, $forCode)
            );

        return new LocaleData(
            code: $locale,
            name: $name,
        );
    }
}
