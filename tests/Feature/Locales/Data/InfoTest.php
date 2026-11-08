<?php

declare(strict_types=1);
use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use PHPUnit\Framework\Attributes\DataProviderExternal;
test('installed', function () {
    expect(Locales::info(Locale::English)->code)->toBe('en');
    expect(Locales::info(Locale::German)->code)->toBe('de');
    expect(Locales::info(Locale::French)->code)->toBe('fr_alias');
    expect(Locales::info(Locale::French->value)->code)->toBe('fr_alias');
});
test('uninstalled', function () {
    expect(Locales::info(Locale::Swedish)->code)->toBe('sv');
    expect(Locales::info(Locale::Swedish->value)->code)->toBe('sv');
});
test('incorrect', function (mixed $value) {
    expect(Locales::info($value)->code)->toBe('fr_alias');
})->with('incorrect locales');
test('invalid fallback', function (mixed $value) {
    app()->setLocale($value);

    expect(Locales::info('foo')->code)->toBe('de');
})->with('incorrect locales');
