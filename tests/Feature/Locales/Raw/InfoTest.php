<?php

declare(strict_types=1);
use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use PHPUnit\Framework\Attributes\DataProviderExternal;
test('installed', function () {
    $locales = Locales::raw();

    expect($locales->info(Locale::English))->toBe('en');
    expect($locales->info(Locale::German))->toBe('de');
    expect($locales->info(Locale::French))->toBe('fr_alias');
    expect($locales->info(Locale::French->value))->toBe('fr_alias');
});
test('uninstalled', function () {
    $locales = Locales::raw();

    expect($locales->info(Locale::Swedish))->toBe('sv');
    expect($locales->info(Locale::Swedish->value))->toBe('sv');
});
test('incorrect', function (mixed $value) {
    $locales = Locales::raw();

    expect($locales->info($value))->toBe('fr_alias');
})->with('incorrect locales');
test('invalid fallback', function (mixed $value) {
    app()->setLocale($value);

    $locales = Locales::raw();

    expect($locales->info('foo'))->toBe('de');
})->with('incorrect locales');
