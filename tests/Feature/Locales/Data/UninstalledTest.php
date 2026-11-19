<?php

declare(strict_types=1);

use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('codes', function () {
    $locales = Locales::data()->uninstalled()->pluck('code')->all();

    expect($locales)->not->toContain(FakeLocale::Custom);
    expect($locales)->not->toContain(app()->getLocale());
    expect($locales)->not->toContain(app()->getFallbackLocale());
});

test('aliases', function () {
    $locales = Locales::data()->uninstalled()->pluck('alias')->all();

    expect($locales)->not->toContain(FakeLocale::Custom);
    expect($locales)->not->toContain(app()->getLocale());
    expect($locales)->not->toContain(app()->getFallbackLocale());
});
