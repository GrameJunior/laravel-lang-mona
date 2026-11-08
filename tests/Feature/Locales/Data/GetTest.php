<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('installed', function () {
    expect(Locales::get(Locale::English)->code)->toBe(FakeLocale::Custom);
    expect(Locales::get(Locale::German)->code)->toBe(FakeLocale::Fallback);
    expect(Locales::get(Locale::French)->code)->toBe(FakeLocale::DefaultAlias);
    expect(Locales::get(Locale::French->value)->code)->toBe(FakeLocale::DefaultAlias);
});
test('uninstalled', function () {
    expect(Locales::get(Locale::Swedish)->code)->toBe(FakeLocale::DefaultAlias);
    expect(Locales::get(Locale::Swedish->value)->code)->toBe(FakeLocale::DefaultAlias);
});
test('incorrect', function (mixed $value) {
    expect(Locales::get($value)->code)->toBe(FakeLocale::DefaultAlias);
})->with('incorrect locales');
test('invalid fallback', function (mixed $value) {
    app()->setLocale($value);

    expect(Locales::get(FakeLocale::Incorrect1)->code)->toBe(FakeLocale::Fallback);
})->with('incorrect locales');
