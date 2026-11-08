<?php

declare(strict_types=1);

use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('main', function () {
    expect(Locales::raw()->isInstalled(FakeLocale::Custom))->toBeTrue();
    expect(Locales::raw()->isInstalled(FakeLocale::Fallback))->toBeTrue();
    expect(Locales::raw()->isInstalled(FakeLocale::Default))->toBeTrue();
    expect(Locales::raw()->isInstalled(FakeLocale::DefaultAlias))->toBeTrue();
});

test('incorrect', function (mixed $locale) {
    ensureLang($locale);

    expect(Locales::raw()->isInstalled($locale))->toBeFalse();
})->with('incorrect locales');
