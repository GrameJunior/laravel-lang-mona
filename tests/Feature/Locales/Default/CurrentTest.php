<?php

declare(strict_types=1);

use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('default', function () {
    Locales::set(FakeLocale::Fallback);

    $locale = Locales::current();

    expect($locale)->toBe(FakeLocale::Fallback);
});

test('aliased', function () {
    Locales::set(FakeLocale::Default);

    $locale = Locales::current();

    expect($locale)->toBe(FakeLocale::DefaultAlias);
    $this->assertNotSame(FakeLocale::Default, $locale);
});

test('incorrect', function () {
    app()->setLocale(FakeLocale::Incorrect1);

    $locale = Locales::current();

    expect($locale)->toBe(FakeLocale::Fallback);
});
