<?php

declare(strict_types=1);

use LaravelLang\CountryNames\CountryNames;
use LaravelLang\LocaleList\Locale;

test('locale', function (Locale $locale) {
    $items = CountryNames::all(locale: $locale);

    expect($items->toArray())
        ->toHaveCount(count(Locale::cases()))
        ->toMatchSnapshot();
})->with([
    Locale::French,
    Locale::German,
    Locale::Russian,
]);
