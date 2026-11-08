<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('installed', function () {
    $locales = Locales::raw();

    expect($locales->get(Locale::English))->toBe(FakeLocale::Custom);
    expect($locales->get(Locale::German))->toBe(FakeLocale::Fallback);
    expect($locales->get(Locale::French))->toBe(FakeLocale::DefaultAlias);
    expect($locales->get(Locale::French->value))->toBe(FakeLocale::DefaultAlias);
});
test('uninstalled', function () {
    $locales = Locales::raw();

    expect($locales->get(Locale::Swedish))->toBe(FakeLocale::DefaultAlias);
    expect($locales->get(Locale::Swedish->value))->toBe(FakeLocale::DefaultAlias);
});
test('incorrect', function (mixed $value) {
    $locales = Locales::raw();

    expect($locales->get($value))->toBe(FakeLocale::DefaultAlias);
})->with('incorrect locales');
test('invalid fallback', function (mixed $value) {
    app()->setLocale($value);

    $locales = Locales::raw();

    expect($locales->get(FakeLocale::Incorrect1))->toBe(
        FakeLocale::Fallback
    );
})->with('incorrect locales');
