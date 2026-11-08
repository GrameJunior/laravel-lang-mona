<?php

declare(strict_types=1);
use LaravelLang\Locales\Facades\Locales;
test('main', function () {
    app()->setFallbackLocale('de');

    $locale = Locales::getFallback()->code;

    expect($locale)->toBe('de');
});
test('alias', function () {
    app()->setFallbackLocale('fr');

    $locale = Locales::getFallback()->code;

    expect($locale)->toBe('fr_alias');
});
