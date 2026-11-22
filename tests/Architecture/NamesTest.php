<?php

declare(strict_types=1);

use LaravelLang\CurrencyNames\CurrencyNames;
use LaravelLang\LocaleNames\LocaleNames;
use LaravelLang\NativeCountryNames\CountryNames;

arch('facade')
    ->expect([
        CountryNames::class,
        CurrencyNames::class,
        LocaleNames::class,
    ])
    ->not->toHavePublicMethodsBesides(['__construct', 'all', 'one']);
