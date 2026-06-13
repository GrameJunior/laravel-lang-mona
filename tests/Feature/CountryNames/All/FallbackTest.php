<?php

declare(strict_types=1);

use LaravelLang\CountryNames\CountryNames;
use LaravelLang\LocaleList\Locale;

test('fallback', function () {
    $items = CountryNames::all();

    expect($items->toArray())
        ->toHaveCount(count(Locale::cases()))
        ->toMatchSnapshot();
});
