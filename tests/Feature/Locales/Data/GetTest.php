<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;

test('installed', function () {
    expect(Locales::get(Locale::English)->code)->toBe('en');
    expect(Locales::get(Locale::German)->code)->toBe('de');
    expect(Locales::get(Locale::French)->code)->toBe('fr_alias');
    expect(Locales::get(Locale::French->value)->code)->toBe('fr_alias');
});
test('uninstalled', function () {
    expect(Locales::get(Locale::Swedish)->code)->toBe('fr_alias');
    expect(Locales::get(Locale::Swedish->value)->code)->toBe('fr_alias');
});
test('incorrect', function (mixed $value) {
    expect(Locales::get($value)->code)->toBe('fr_alias');
})->with('incorrect locales');
test('invalid fallback', function (mixed $value) {
    app()->setLocale($value);

    expect(Locales::get('foo')->code)->toBe('de');
})->with('incorrect locales');
