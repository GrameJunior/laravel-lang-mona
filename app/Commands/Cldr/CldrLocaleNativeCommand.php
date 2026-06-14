<?php

declare(strict_types=1);

namespace LaravelLang\Mona\Commands\Cldr;

use LaravelLang\LocaleNames\LocaleData;
use LaravelLang\Mona\Concerns\HasLanguages;
use LaravelLang\Mona\Concerns\HasLocaleMetadata;
use LaravelLang\Mona\Concerns\HasNative;
use RuntimeException;

final class CldrLocaleNativeCommand extends CldrCommand
{
    use HasNative;
    use HasLanguages;
    use HasLocaleMetadata;

    protected string $targetDirectory = __DIR__ . '/../../../src/LocaleNames/data';

    protected function perform(string $forCode, string $locale): LocaleData
    {
        $forLanguages = $this->languages($locale);
        $fallback     = $this->languages($this->fallback);

        $code = $this->localeCode($locale);
        $base = $this->baseCode($code);

        $name = $forLanguages[$code]
            ?? $forLanguages[$base]
            ?? $fallback[$code]
            ?? $fallback[$base]
            ?? throw new RuntimeException(
                sprintf('Unable to find CLDR locale name for [%s].', $locale)
            );

        $metadata = $this->localeMetadata($locale);

        return new LocaleData(
            code     : $locale,
            name     : $name,
            regional : $metadata['regional'],
            type     : $metadata['type'],
            direction: $metadata['direction'],
        );
    }
}
