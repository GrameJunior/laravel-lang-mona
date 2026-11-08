<?php

declare(strict_types=1);
use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use PHPUnit\Framework\Attributes\DataProviderExternal;
test('installed', function () {
    $locales = Locales::raw();

    expect($locales->get(Locale::English))->toBe('en');
    expect($locales->get(Locale::German))->toBe('de');
    expect($locales->get(Locale::French))->toBe('fr_alias');
    expect($locales->get(Locale::French->value))->toBe('fr_alias');
});
test('uninstalled', function () {
    $locales = Locales::raw();

    expect($locales->get(Locale::Swedish))->toBe('fr_alias');
    expect($locales->get(Locale::Swedish->value))->toBe('fr_alias');
});
test('incorrect', function (mixed $value) {
    $locales = Locales::raw();

    expect($locales->get($value))->toBe('fr_alias');
})->with('incorrect locales');
test('invalid fallback', function (mixed $value) {
    app()->setLocale($value);

    $locales = Locales::raw();

    expect($locales->get('foo'))->toBe('de');
})->with('incorrect locales');
