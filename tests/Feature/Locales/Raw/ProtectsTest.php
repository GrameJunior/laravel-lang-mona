<?php

declare(strict_types=1);
use LaravelLang\Locales\Facades\Locales;
test('main', function () {
    $locales = Locales::raw()->protects();

    expect($locales)->toContain('fr_alias');
    expect($locales)->toContain('de');
    expect($locales)->not->toContain('en');

    expect($locales)->toHaveCount(2);
});
test('incorrect', function () {
    app()->setLocale('foo');
    app()->setFallbackLocale('bar');

    $locales = Locales::raw()->protects();

    expect($locales)->toContain('en');

    expect($locales)->toHaveCount(1);
});
