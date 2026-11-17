<?php

declare(strict_types=1);

namespace LaravelLang\NativeCountryNames\Data;

readonly class CountryData
{
    public function __construct(
        public string $locale,
        public string $code,
        public string $native,
        public string $localized,
    ) {}
}
