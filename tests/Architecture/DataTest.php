<?php

declare(strict_types=1);

use LaravelLang\CurrencyNames\CurrencyData;
use LaravelLang\NativeCountryNames\CountryData;
use LaravelLang\NativeLocaleNames\LocaleData;

arch('protect')
    ->expect([
        'LaravelLang\Publisher\Data',
        CountryData::class,
        CurrencyData::class,
        LocaleData::class,
    ])
    ->toBeFinal()
    ->toBeReadonly();

arch('methods')
    ->expect([
        CountryData::class,
        CurrencyData::class,
        LocaleData::class,
    ])
    ->not->toHavePrivateMethods()
    ->not->toHaveProtectedMethods()
    ->not->toHavePublicMethodsBesides(['__construct']);

arch('extends')
    ->expect([
        CountryData::class,
        CurrencyData::class,
        LocaleData::class,
    ])
    ->toExtendNothing();
