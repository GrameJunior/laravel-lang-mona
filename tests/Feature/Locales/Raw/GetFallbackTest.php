<?php

declare(strict_types=1);

use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('main', function () {
    app()->setFallbackLocale(FakeLocale::Fallback);

    $locale = Locales::raw()->getFallback();

    expect($locale)->toBe(FakeLocale::Fallback);
});
test('alias', function () {
    app()->setFallbackLocale(FakeLocale::Default);

    $locale = Locales::raw()->getFallback();

    expect($locale)->toBe(FakeLocale::DefaultAlias);
});
