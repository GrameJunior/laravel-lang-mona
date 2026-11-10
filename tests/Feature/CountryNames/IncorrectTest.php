<?php

declare(strict_types=1);

use LaravelLang\NativeCountryNames\CountryNames;

test('checks for a match using the locale string value')
    ->with('unknown locales')
    ->expect(fn (string $locale) => CountryNames::get($locale))
    ->toBeSameCount()
    ->toBeCompileLocales()
    ->not->toBeEmpty();
