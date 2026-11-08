<?php

declare(strict_types=1);
use LaravelLang\Locales\Facades\Locales;
test('main', function () {
    $locales = Locales::notInstalled()->pluck('code')->all();

    expect($locales)->not->toContain('en');
    expect($locales)->not->toContain(app()->getLocale());
    expect($locales)->not->toContain(app()->getFallbackLocale());
});
