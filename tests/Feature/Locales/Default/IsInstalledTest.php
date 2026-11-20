<?php

declare(strict_types=1);

use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('main', function (): void {
    expect(Locales::isInstalled(FakeLocale::Custom))->toBeTrue();
    expect(Locales::isInstalled(FakeLocale::Fallback))->toBeTrue();
    expect(Locales::isInstalled(FakeLocale::Default))->toBeTrue();
    expect(Locales::isInstalled(FakeLocale::DefaultAlias))->toBeTrue();
});

test('incorrect', function (mixed $locale): void {
    ensureLang($locale);

    expect(Locales::isInstalled($locale))->toBeFalse();
})->with('incorrect locales');
