<?php

declare(strict_types=1);
use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
test('main', function () {
    expect(Locales::raw()->isProtected('fr'))->toBeTrue();
    expect(Locales::raw()->isProtected('fr_alias'))->toBeTrue();
    expect(Locales::raw()->isProtected(Locale::French))->toBeTrue();

    expect(Locales::raw()->isProtected(Locale::German))->toBeTrue();
});
test('not protected', function () {
    expect(Locales::raw()->isProtected(Locale::English))->toBeFalse();
    expect(Locales::raw()->isProtected(Locale::Swedish))->toBeFalse();
});
test('incorrect', function () {
    app()->setLocale('foo');
    app()->setFallbackLocale('bar');

    expect(Locales::raw()->isProtected('foo'))->toBeFalse();
    expect(Locales::raw()->isProtected('bar'))->toBeFalse();
});
