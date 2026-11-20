<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('list', function (Locale $locale): void {
    $asEnum   = Locales::isAvailable($locale);
    $asString = Locales::isAvailable($locale->value);

    expect($asEnum)->toBeTrue();
    expect($asString)->toBeTrue();
})->with('enum locales');
test('alias', function (): void {
    $main  = Locales::isAvailable(FakeLocale::Default);
    $alias = Locales::isAvailable(FakeLocale::DefaultAlias);

    expect($main)->toBeTrue();
    expect($alias)->toBeTrue();
});
test('empty', function (): void {
    $asNull   = Locales::isAvailable(null);
    $asString = Locales::isAvailable('');

    expect($asNull)->toBeFalse();
    expect($asString)->toBeFalse();
});
test('incorrect', function (): void {
    $available = Locales::isAvailable(FakeLocale::Incorrect1);

    expect($available)->toBeFalse();
});
