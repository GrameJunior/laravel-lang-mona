<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('installed', function () {
    expect(Locales::info(Locale::English)->code)->toBe(FakeLocale::Custom);
    expect(Locales::info(Locale::German)->code)->toBe(FakeLocale::Fallback);
    expect(Locales::info(Locale::French)->code)->toBe(FakeLocale::DefaultAlias);
    expect(Locales::info(Locale::French->value)->code)->toBe(FakeLocale::DefaultAlias);
});
test('uninstalled', function () {
    expect(Locales::info(Locale::Swedish)->code)->toBe(FakeLocale::Swedish);
    expect(Locales::info(Locale::Swedish->value)->code)->toBe(FakeLocale::Swedish);
});
test('incorrect', function (mixed $value) {
    expect(Locales::info($value)->code)->toBe(FakeLocale::DefaultAlias);
})->with('incorrect locales');
test('invalid fallback', function (mixed $value) {
    app()->setLocale($value);

    expect(Locales::info(FakeLocale::Incorrect1)->code)->toBe(FakeLocale::Fallback);
})->with('incorrect locales');
