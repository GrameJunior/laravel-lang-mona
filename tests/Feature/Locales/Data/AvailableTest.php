<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;

test('correct', function () {
    $locales = Locales::available()->pluck('code')->all();

    expect($locales)->toHaveCount(count(Locale::cases()));

    expect($locales)->toContain('fr_alias');
    expect($locales)->not->toContain('fr');
});
test('incorrect', function () {
    $locales = Locales::available()->pluck('code')->all();

    expect($locales)->not->toContain('foo');
});
