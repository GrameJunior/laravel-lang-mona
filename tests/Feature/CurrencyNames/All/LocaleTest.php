<?php

declare(strict_types=1);

use LaravelLang\CurrencyNames\CurrencyNames;
use LaravelLang\LocaleList\Locale;

test('locale', function (Locale $locale) {
    $items = CurrencyNames::all(locale: $locale);

    expect($items->toArray())
        ->toHaveCount(count(Locale::cases()))
        ->toMatchSnapshot();
})->with([
    Locale::French,
    Locale::German,
    Locale::Russian,
]);
