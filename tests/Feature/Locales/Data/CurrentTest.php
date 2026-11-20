<?php

declare(strict_types=1);

use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('default', function (): void {
    Locales::set(FakeLocale::Fallback);

    $locale = Locales::data()->current();

    expect($locale->code)->toBe(FakeLocale::Fallback);
    expect($locale->alias)->toBe(FakeLocale::Fallback);
});

test('aliased', function (): void {
    Locales::set(FakeLocale::Default);

    $locale = Locales::data()->current();

    expect($locale->code)->not->toBe(FakeLocale::DefaultAlias);
    expect($locale->alias)->toBe(FakeLocale::DefaultAlias);
    $this->assertNotSame(FakeLocale::Default, $locale->localized);
});

test('incorrect', function (): void {
    app()->setLocale(FakeLocale::Incorrect1);

    $locale = Locales::data()->current();

    expect($locale->code)->toBe(FakeLocale::Fallback);
});
