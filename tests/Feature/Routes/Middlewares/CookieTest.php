<?php

declare(strict_types=1);

use LaravelLang\Config\Services\RouteNameLangConfig;
use Workbench\App\Constants\FakeTranslation;

use function Pest\Laravel\withCredentials;

test('direct locale', function (string $locale, string $translated): void {
    $foo = 'test';

    setCustomLocale();

    withCredentials()
        ->withCookie(app(RouteNameLangConfig::class)->cookie, $locale)
        ->getJson(route('via.cookie', ['key' => $foo]))
        ->assertSuccessful()
        ->assertJsonPath($foo, $translated);

    assertEventDispatched();
})->with('direct locales');

test('aliased locale', function (string $locale): void {
    $foo = 'test';

    setCustomLocale();

    withCredentials()
        ->withCookie(app(RouteNameLangConfig::class)->cookie, $locale)
        ->getJson(route('via.cookie', ['key' => $foo]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventDispatched();
})->with('aliased locales');

test('empty locale', function (int|string|null $locale): void {
    $foo = 'test';

    withCredentials()
        ->withCookie(app(RouteNameLangConfig::class)->cookie, (string) $locale)
        ->getJson(route('via.cookie', ['key' => $foo]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
})->with('incorrect locales');

test('ignores a locale cookie containing an array', function (): void {
    withCredentials()
        ->withUnencryptedCookies([app(RouteNameLangConfig::class)->cookie => ['de']])
        ->getJson(route('via.cookie', ['key' => 'test']))
        ->assertSuccessful()
        ->assertJsonPath('test', FakeTranslation::French);

    assertEventNotDispatched();
});

test('uninstalled locale', function (string $locale): void {
    $foo = 'test';

    withCredentials()
        ->withCookie(app(RouteNameLangConfig::class)->cookie, $locale)
        ->getJson(route('via.cookie', ['key' => $foo]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
})->with('uninstalled locales');

test('unknown locale', function (int|string $locale): void {
    $foo = 'test';

    withCredentials()
        ->withCookie(app(RouteNameLangConfig::class)->cookie, (string) $locale)
        ->getJson(route('via.cookie', ['key' => $foo]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
})->with('unknown locales');
