<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('installed', function () {
    $locales = Locales::raw();

    expect($locales->info(Locale::English))->toBe(FakeLocale::Custom);
    expect($locales->info(Locale::German))->toBe(FakeLocale::Fallback);
    expect($locales->info(Locale::French))->toBe(FakeLocale::DefaultAlias);
    expect($locales->info(Locale::French->value))->toBe(FakeLocale::DefaultAlias);
});
test('uninstalled', function () {
    $locales = Locales::raw();

    expect($locales->info(Locale::Swedish))->toBe(FakeLocale::Uninstalled);
    expect($locales->info(Locale::Swedish->value))->toBe(FakeLocale::Uninstalled);
});
test('incorrect', function (mixed $value) {
    $locales = Locales::raw();

    expect($locales->info($value))->toBe(FakeLocale::DefaultAlias);
})->with('incorrect locales');
test('invalid fallback', function (mixed $value) {
    app()->setLocale($value);

    $locales = Locales::raw();

    expect($locales->info(FakeLocale::Incorrect1))->toBe(
        FakeLocale::Fallback
    );
})->with('incorrect locales');
