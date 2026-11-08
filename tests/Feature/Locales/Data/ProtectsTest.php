<?php

declare(strict_types=1);

use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('main', function () {
    $locales = Locales::protects()->pluck('code')->all();

    expect($locales)->toContain(FakeLocale::DefaultAlias);
    expect($locales)->toContain(FakeLocale::Fallback);
    expect($locales)->not->toContain(FakeLocale::Custom);

    expect($locales)->toHaveCount(2);
});
test('incorrect', function () {
    app()->setLocale(FakeLocale::Incorrect1);
    app()->setFallbackLocale(FakeLocale::Incorrect2);

    $locales = Locales::protects()->pluck('code')->all();

    expect($locales)->toContain(FakeLocale::Custom);

    expect($locales)->toHaveCount(1);
});
