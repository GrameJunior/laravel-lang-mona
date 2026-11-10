<?php

declare(strict_types=1);

use LaravelLang\NativeCountryNames\CountryNames;
use LaravelLang\NativeCountryNames\Enums\SortBy;

test('checks default sorting', function (string $locale) {
    expect(CountryNames::get($locale))
        ->toBeSameCount()
        ->toBeLocale($locale)
        ->not->toBeEmpty();
})->with('string locales');

test('checks sorting by key', function (string $locale) {
    expect(CountryNames::get($locale, SortBy::Key))
        ->toBeSameCount()
        ->toBeLocale($locale, SortBy::Key)
        ->not->toBeEmpty();
})->with('string locales');

test('checks sorting by value', function (string $locale) {
    expect(CountryNames::get($locale, SortBy::Value))
        ->toBeSameCount()
        ->toBeLocale($locale, SortBy::Value)
        ->not->toBeEmpty();
})->with('string locales');
