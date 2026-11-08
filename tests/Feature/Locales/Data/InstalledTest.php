<?php

declare(strict_types=1);

use LaravelLang\Locales\Facades\Locales;

test('correct', function () {
    $locales = Locales::installed()->pluck('code')->all();

    expect($locales)->toContain('en');
    expect($locales)->toContain('de');
    expect($locales)->toContain('fr_alias');

    expect($locales)->toHaveCount(3);
});
test('incorrect', function () {
    ensureLang('foo');

    $locales = Locales::installed()->pluck('code')->all();

    expect($locales)->toContain('en');
    expect($locales)->toContain('de');
    expect($locales)->toContain('fr_alias');

    expect($locales)->not->toContain('foo');

    expect($locales)->toHaveCount(3);
});
