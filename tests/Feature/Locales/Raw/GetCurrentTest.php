<?php

declare(strict_types=1);
use LaravelLang\Locales\Facades\Locales;
test('main', function () {
    Locales::set('de');

    $locale = Locales::raw()->getCurrent();

    expect($locale)->toBe('de');
});
test('aliased', function () {
    Locales::set('fr');

    $locale = Locales::raw()->getCurrent();

    expect($locale)->toBe('fr_alias');
    $this->assertNotSame('fr', $locale);
});
