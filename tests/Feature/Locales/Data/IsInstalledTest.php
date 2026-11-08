<?php

declare(strict_types=1);

use LaravelLang\Locales\Facades\Locales;

test('main', function () {
    expect(Locales::isInstalled('en'))->toBeTrue();
    expect(Locales::isInstalled('de'))->toBeTrue();
    expect(Locales::isInstalled('fr'))->toBeTrue();
    expect(Locales::isInstalled('fr_alias'))->toBeTrue();
});
test('empty', function () {
    $asNull   = Locales::isInstalled(null);
    $asString = Locales::isInstalled('');

    expect($asNull)->toBeFalse();
    expect($asString)->toBeFalse();
});
test('incorrect', function () {
    ensureLang('bar');

    $foo = Locales::isInstalled('foo');
    $bar = Locales::isInstalled('bar');

    expect($foo)->toBeFalse();
    expect($bar)->toBeFalse();
});
