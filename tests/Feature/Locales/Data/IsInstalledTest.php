<?php

declare(strict_types=1);

use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('main', function () {
    expect(Locales::isInstalled(FakeLocale::Custom))->toBeTrue();
    expect(Locales::isInstalled(FakeLocale::Fallback))->toBeTrue();
    expect(Locales::isInstalled(FakeLocale::Default))->toBeTrue();
    expect(Locales::isInstalled(FakeLocale::DefaultAlias))->toBeTrue();
});
test('empty', function () {
    $asNull   = Locales::isInstalled(null);
    $asString = Locales::isInstalled('');

    expect($asNull)->toBeFalse();
    expect($asString)->toBeFalse();
});
test('incorrect', function () {
    ensureLang(FakeLocale::Incorrect2);

    $foo = Locales::isInstalled(FakeLocale::Incorrect1);
    $bar = Locales::isInstalled(FakeLocale::Incorrect2);

    expect($foo)->toBeFalse();
    expect($bar)->toBeFalse();
});
