<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\LocaleNames\LocaleNames;
use LaravelLang\Support\Enums\SortBy;

test('sorted', function (SortBy $sortBy) {
    $items = LocaleNames::all(
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
