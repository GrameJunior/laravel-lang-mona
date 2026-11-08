<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('list', function (Locale $locale) {
    $asEnum   = Locales::raw()->isAvailable($locale);
    $asString = Locales::raw()->isAvailable($locale->value);

    expect($asEnum)->toBeTrue();
    expect($asString)->toBeTrue();
})->with('correct locales');
test('alias', function () {
    $main  = Locales::raw()->isAvailable(FakeLocale::Default);
    $alias = Locales::raw()->isAvailable(FakeLocale::DefaultAlias);

    expect($main)->toBeTrue();
    expect($alias)->toBeTrue();
});
test('empty', function () {
    $asNull   = Locales::raw()->isAvailable(null);
    $asString = Locales::raw()->isAvailable('');

    expect($asNull)->toBeFalse();
    expect($asString)->toBeFalse();
});
test('incorrect', function () {
    $available = Locales::raw()->isAvailable(FakeLocale::Incorrect1);

    expect($available)->toBeFalse();
});
