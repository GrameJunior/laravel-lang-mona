<?php

declare(strict_types=1);
use LaravelLang\Locales\Facades\Locales;
test('main', function () {
    $locales = Locales::raw()->notInstalled();

    expect($locales)->not->toContain('en');
    expect($locales)->not->toContain(app()->getLocale());
    expect($locales)->not->toContain(app()->getFallbackLocale());
});
