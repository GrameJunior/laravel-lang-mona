<?php

declare(strict_types=1);

use LaravelLang\NativeCountryNames\CountryNames;

test('checks for empty values being passed')
    ->with('locales-empty')
    ->expect(fn (?string $locale) => CountryNames::get($locale))
    ->toBeSameCount()
    ->toBeCompileLocales()
    ->not->toBeEmpty();
