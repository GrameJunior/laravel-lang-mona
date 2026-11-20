<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('main', function (): void {
    expect(Locales::isProtected(FakeLocale::Default))->toBeTrue();
    expect(Locales::isProtected(FakeLocale::DefaultAlias))->toBeTrue();
    expect(Locales::isProtected(Locale::French))->toBeTrue();

    expect(Locales::isProtected(Locale::German))->toBeTrue();
});
test('not protected', function (): void {
    expect(Locales::isProtected(Locale::English))->toBeFalse();
    expect(Locales::isProtected(Locale::Swedish))->toBeFalse();
    expect(Locales::isProtected(null))->toBeFalse();
});
test('incorrect', function (): void {
    app()->setLocale(FakeLocale::Incorrect1);
    app()->setFallbackLocale(FakeLocale::Incorrect2);

    expect(Locales::isProtected(FakeLocale::Incorrect1))->toBeFalse();
    expect(Locales::isProtected(FakeLocale::Incorrect2))->toBeFalse();
});
