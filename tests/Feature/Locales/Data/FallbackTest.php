<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('default', function (): void {
    app()->setFallbackLocale(FakeLocale::Fallback);

    $locale = Locales::data()->fallback();

    expect($locale->code)->toBe(FakeLocale::Fallback);
    expect($locale->alias)->toBe(FakeLocale::Fallback);
});
test('alias', function (): void {
    app()->setFallbackLocale(FakeLocale::Default);

    $locale = Locales::data()->fallback();

    expect($locale->code)->not->toBe(FakeLocale::DefaultAlias);
    expect($locale->alias)->toBe(FakeLocale::DefaultAlias);
});

test('incorrect', function (): void {
    app()->setFallbackLocale(FakeLocale::Incorrect1);

    $locale = Locales::data()->fallback();

    expect($locale->code)->toBe(Locale::English->value);
    expect($locale->alias)->toBe(Locale::English->value);
});
