<?php

declare(strict_types=1);

use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('default', function (): void {
    Locales::set(FakeLocale::Fallback);

    $locale = Locales::current();

    expect($locale)->toBe(FakeLocale::Fallback);
});

test('aliased', function (): void {
    Locales::set(FakeLocale::Default);

    $locale = Locales::current();

    expect($locale)->toBe(FakeLocale::DefaultAlias);
    $this->assertNotSame(FakeLocale::Default, $locale);
});

test('incorrect', function (): void {
    app()->setLocale(FakeLocale::Incorrect1);

    $locale = Locales::current();

    expect($locale)->toBe(FakeLocale::Fallback);
});
