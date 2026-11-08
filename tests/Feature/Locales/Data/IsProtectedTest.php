<?php

declare(strict_types=1);
use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
test('main', function () {
    expect(Locales::isProtected('fr'))->toBeTrue();
    expect(Locales::isProtected('fr_alias'))->toBeTrue();
    expect(Locales::isProtected(Locale::French))->toBeTrue();

    expect(Locales::isProtected(Locale::German))->toBeTrue();
});
test('not protected', function () {
    expect(Locales::isProtected(Locale::English))->toBeFalse();
    expect(Locales::isProtected(Locale::Swedish))->toBeFalse();
});
test('incorrect', function () {
    app()->setLocale('foo');
    app()->setFallbackLocale('bar');

    expect(Locales::isProtected('foo'))->toBeFalse();
    expect(Locales::isProtected('bar'))->toBeFalse();
});
