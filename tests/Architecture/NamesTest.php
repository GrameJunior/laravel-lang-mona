<?php

declare(strict_types=1);

use LaravelLang\CurrencyNames\CurrencyNames;
use LaravelLang\NativeCountryNames\CountryNames;
use LaravelLang\NativeLocaleNames\LocaleNames;

arch('facade')
    ->expect([
        CountryNames::class,
        CurrencyNames::class,
        LocaleNames::class,
    ])
    ->not->toHavePublicMethodsBesides(['__construct', 'all', 'one']);
