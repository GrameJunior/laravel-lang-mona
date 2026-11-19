<?php

declare(strict_types=1);

use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('main', function () {
    $locales = Locales::uninstalled();

    expect($locales)->not->toContain(FakeLocale::Custom);
    expect($locales)->not->toContain(app()->getLocale());
    expect($locales)->not->toContain(app()->getFallbackLocale());
});
