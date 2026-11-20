<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('default', function (): void {
    app()->setFallbackLocale(FakeLocale::Fallback);

    $locale = Locales::fallback();

    expect($locale)->toBe(FakeLocale::Fallback);
});
test('alias', function (): void {
    app()->setFallbackLocale(FakeLocale::Default);

    $locale = Locales::fallback();

    expect($locale)->toBe(FakeLocale::DefaultAlias);
});

test('incorrect', function (): void {
    app()->setFallbackLocale(FakeLocale::Incorrect1);

    $locale = Locales::fallback();

    expect($locale)->toBe(Locale::English->value);
});
