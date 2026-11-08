<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('main', function () {
    expect(Locales::raw()->isProtected(FakeLocale::Default))->toBeTrue();
    expect(Locales::raw()->isProtected(FakeLocale::DefaultAlias))->toBeTrue();
    expect(Locales::raw()->isProtected(Locale::French))->toBeTrue();

    expect(Locales::raw()->isProtected(Locale::German))->toBeTrue();
});
test('not protected', function () {
    expect(Locales::raw()->isProtected(Locale::English))->toBeFalse();
    expect(Locales::raw()->isProtected(Locale::Swedish))->toBeFalse();
});
test('incorrect', function () {
    app()->setLocale(FakeLocale::Incorrect1);
    app()->setFallbackLocale(FakeLocale::Incorrect2);

    expect(Locales::raw()->isProtected(FakeLocale::Incorrect1))->toBeFalse();
    expect(Locales::raw()->isProtected(FakeLocale::Incorrect2))->toBeFalse();
});
