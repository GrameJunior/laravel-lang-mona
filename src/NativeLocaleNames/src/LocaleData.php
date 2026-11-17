<?php

declare(strict_types=1);

namespace LaravelLang\NativeLocaleNames;

readonly class LocaleData
{
    public function __construct(
        public string $code,
        public string $alias,
        public string $native,
        public string $localized,
    ) {}
}
