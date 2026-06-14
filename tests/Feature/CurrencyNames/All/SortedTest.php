<?php

declare(strict_types=1);

use LaravelLang\CurrencyNames\CurrencyNames;
use LaravelLang\LocaleList\Locale;
use LaravelLang\Support\Enums\SortBy;

test('sorted', function (SortBy $sortBy) {
    $items = CurrencyNames::all(
        locale: Locale::French,
        sortBy: $sortBy
    );

    expect($items->toArray())
        ->toHaveCount(count(Locale::cases()))
        ->toMatchSnapshot();
})->with([
    SortBy::None,
    SortBy::Key,
    SortBy::Value,
]);
