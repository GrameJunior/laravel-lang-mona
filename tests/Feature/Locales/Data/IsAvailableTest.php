<?php

declare(strict_types=1);
use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use PHPUnit\Framework\Attributes\DataProviderExternal;
test('list', function (Locale $locale) {
    $asEnum   = Locales::isAvailable($locale);
    $asString = Locales::isAvailable($locale->value);

    expect($asEnum)->toBeTrue();
    expect($asString)->toBeTrue();
})->with('correct locales');
test('alias', function () {
    $main  = Locales::isAvailable('fr');
    $alias = Locales::isAvailable('fr_alias');

    expect($main)->toBeTrue();
    expect($alias)->toBeTrue();
});
test('empty', function () {
    $asNull   = Locales::isAvailable(null);
    $asString = Locales::isAvailable('');

    expect($asNull)->toBeFalse();
    expect($asString)->toBeFalse();
});
test('incorrect', function () {
    $available = Locales::isAvailable('foo');

    expect($available)->toBeFalse();
});
