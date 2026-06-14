<?php

declare(strict_types=1);

use LaravelLang\CurrencyNames\CurrencyNames;
use LaravelLang\LocaleList\Locale;

test('default', function () {
    $items = CurrencyNames::all();

    expect($items->toArray())
        ->toHaveCount(count(Locale::cases()))
        ->toMatchSnapshot();
});
