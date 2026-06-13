<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\LocaleNames\LocaleNames;

test('locale', function (Locale $locale) {
    $items = LocaleNames::all(locale: $locale);

    expect($items->toArray())
        ->toHaveCount(count(Locale::cases()))
        ->toMatchSnapshot();
})->with([
    Locale::French,
    Locale::German,
    Locale::Russian,
]);
