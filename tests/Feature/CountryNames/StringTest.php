<?php

declare(strict_types=1);

use LaravelLang\NativeCountryNames\CountryNames;

test('checks for a match using the locale string value', function (string $locale) {
    expect(CountryNames::get($locale))
        ->toBeSameCount()
        ->toBeLocale($locale)
        ->not->toBeEmpty();
})->with('string locales');
