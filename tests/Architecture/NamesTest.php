<?php

declare(strict_types=1);

use LaravelLang\LocaleNames\LocaleNames;
use LaravelLang\NativeCountryNames\CountryNames;
use LaravelLang\NativeCurrencyNames\CurrencyNames;

arch('facade')
    ->expect([
        CountryNames::class,
        CurrencyNames::class,
        LocaleNames::class,
    ])
    ->not->toHavePublicMethodsBesides(['__construct', 'all', 'one']);
