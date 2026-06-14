<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\LocaleNames\LocaleNames;

test('default', function () {
    $items = LocaleNames::all();

    expect($items->toArray())
        ->toHaveCount(count(Locale::cases()))
        ->toMatchSnapshot();
});
