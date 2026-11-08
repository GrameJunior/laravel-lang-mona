<?php

declare(strict_types=1);
use LaravelLang\Locales\Facades\Locales;
test('correct', function () {
    $locales = Locales::raw()->available();

    expect($locales)->toContain('fr_alias');
    expect($locales)->not->toContain('fr');
});
test('incorrect', function () {
    $locales = Locales::raw()->available();

    expect($locales)->not->toContain('foo');
});
