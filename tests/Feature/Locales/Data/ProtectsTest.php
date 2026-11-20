<?php

declare(strict_types=1);

use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('main codes', function (): void {
    $locales = Locales::data()->protects()->pluck('code')->all();

    expect($locales)->not->toContain(FakeLocale::DefaultAlias);
    expect($locales)->toContain(FakeLocale::Fallback);
    expect($locales)->not->toContain(FakeLocale::Custom);

    expect($locales)->toHaveCount(2);
});

test('main aliases', function (): void {
    $locales = Locales::data()->protects()->pluck('alias')->all();

    expect($locales)->toContain(FakeLocale::DefaultAlias);
    expect($locales)->toContain(FakeLocale::Fallback);
    expect($locales)->not->toContain(FakeLocale::Custom);

    expect($locales)->toHaveCount(2);
});

test('incorrect', function (): void {
    app()->setLocale(FakeLocale::Incorrect1);
    app()->setFallbackLocale(FakeLocale::Incorrect2);

    $codes   = Locales::data()->protects()->pluck('code')->all();
    $aliases = Locales::data()->protects()->pluck('alias')->all();

    expect($codes)->toContain(FakeLocale::Custom);
    expect($aliases)->toContain(FakeLocale::Custom);

    expect($codes)->toHaveCount(1);
    expect($aliases)->toHaveCount(1);
});
