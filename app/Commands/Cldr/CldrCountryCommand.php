<?php

declare(strict_types=1);

namespace LaravelLang\Mona\Commands\Cldr;

use LaravelLang\CountryNames\CountryData;
use LaravelLang\Mona\Concerns\HasTerritories;

final class CldrCountryCommand extends CldrCommand
{
    use HasTerritories;

    protected string $targetDirectory = __DIR__ . '/../../../src/CountryNames/data';

    protected string $sourceDirectory = __DIR__ . '/../../../node_modules/cldr-localenames-full/main';

    protected string $likelySubtagsPath = __DIR__ . '/../../../node_modules/cldr-core/supplemental/likelySubtags.json';

    protected function perform(string $forCode, string $locale): CountryData
    {
        $code = $this->countryCode($locale);

        return new CountryData(
            code: $code,
            name: $this->countryName($forCode, $code),
        );
    }
}
