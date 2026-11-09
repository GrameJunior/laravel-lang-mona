<?php

declare(strict_types=1);

use LaravelLang\Config\Facades\Config;

use Workbench\App\Constants\FakeTranslation;

use function Pest\Laravel\withCredentials;

test('main locale', function (string $locale) {
    $foo = 'test';

    withCredentials()
        ->withCookie(Config::shared()->routes->names->cookie, $locale)
        ->getJson(route('via.cookie', ['key' => $foo]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventDispatched();
})->with('main locales');

test('aliased locale', function (string $locale) {
    $foo = 'test';

    withCredentials()
        ->withCookie(Config::shared()->routes->names->cookie, $locale)
        ->getJson(route('via.cookie', ['key' => $foo]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::German);

    assertEventDispatched();
})->with('aliased locales');

test('empty locale', function (int|string|null $locale) {
    $foo = 'test';

    withCredentials()
        ->withCookie(Config::shared()->routes->names->cookie, (string) $locale)
        ->getJson(route('via.cookie', ['key' => $foo]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
})->with('incorrect locales');

test('uninstalled locale', function (string $locale) {
    $foo = 'test';

    withCredentials()
        ->withCookie(Config::shared()->routes->names->cookie, $locale)
        ->getJson(route('via.cookie', ['key' => $foo]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventDispatched();
})->with('uninstalled locales');

test('unknown locale', function (int|string $locale) {
    $foo = 'test';

    withCredentials()
        ->withCookie(Config::shared()->routes->names->cookie, (string) $locale)
        ->getJson(route('via.cookie', ['key' => $foo]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventDispatched();
})->with('unknown locales');
