<?php

declare(strict_types=1);

use LaravelLang\Locales\Facades\Locales;

test('main', function () {
    expect(Locales::raw()->isInstalled('en'))->toBeTrue();
    expect(Locales::raw()->isInstalled('de'))->toBeTrue();
    expect(Locales::raw()->isInstalled('fr'))->toBeTrue();
    expect(Locales::raw()->isInstalled('fr_alias'))->toBeTrue();
});
test('empty', function () {
    $asNull   = Locales::raw()->isInstalled(null);
    $asString = Locales::raw()->isInstalled('');

    expect($asNull)->toBeFalse();
    expect($asString)->toBeFalse();
});
test('incorrect', function () {
    ensureLang('bar');

    $foo = Locales::raw()->isInstalled('foo');
    $bar = Locales::raw()->isInstalled('bar');

    expect($foo)->toBeFalse();
    expect($bar)->toBeFalse();
});
