<?php

declare(strict_types=1);

use LaravelLang\Config\Facades\Config;

use Workbench\App\Constants\FakeTranslation;

use function Pest\Laravel\getJson;

test('direct locale', function (string $locale, string $translated) {
    $foo = 'test';

    setCustomLocale();

    getJson(route('via.header', ['key' => $foo]), [
        Config::shared()->routes->names->header => $locale,
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, $translated);

    assertEventDispatched();
})->with('direct locales');

test('aliased locale', function (string $locale) {
    $foo = 'test';

    setCustomLocale();

    getJson(route('via.header', ['key' => $foo]), [
        Config::shared()->routes->names->header => $locale,
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventDispatched();
})->with('aliased locales');

test('empty locale', function (int|string|null $locale) {
    $foo = 'test';

    getJson(route('via.header', ['key' => $foo]), [
        Config::shared()->routes->names->header => $locale,
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
})->with('incorrect locales');

test('uninstalled locale', function (string $locale) {
    $foo = 'test';

    getJson(route('via.header', ['key' => $foo]), [
        Config::shared()->routes->names->header => $locale,
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
})->with('uninstalled locales');

test('unknown locale', function (int|string $locale) {
    $foo = 'test';

    getJson(route('via.header', ['key' => $foo]), [
        Config::shared()->routes->names->header => $locale,
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
})->with('unknown locales');

test('multiple accept-language', function () {
    $foo = 'test';

    setCustomLocale();

    getJson(route('via.header', ['key' => $foo]), [
        Config::shared()->routes->names->header => 'fr-FR,fr;q=0.8,de',
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventDispatched();
});

test('custom header', function () {
    config(['localization.routes.names.header' => 'X-Custom-Lang']);

    $foo = 'test';

    setCustomLocale();

    getJson(route('via.header', ['key' => $foo]), [
        'X-Custom-Lang' => 'fr-FR,fr;q=0.8,de',
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventDispatched();
});
