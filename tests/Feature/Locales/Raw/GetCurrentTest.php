<?php

declare(strict_types=1);

use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('main', function () {
    Locales::set(FakeLocale::Fallback);

    $locale = Locales::raw()->getCurrent();

    expect($locale)->toBe(FakeLocale::Fallback);
});
test('aliased', function () {
    Locales::set(FakeLocale::Default);

    $locale = Locales::raw()->getCurrent();

    expect($locale)->toBe(FakeLocale::DefaultAlias);
    $this->assertNotSame(FakeLocale::Default, $locale);
});
