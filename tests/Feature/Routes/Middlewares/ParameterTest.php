<?php

declare(strict_types=1);

use Workbench\App\Constants\FakeTranslation;

use function Pest\Laravel\getJson;

test('main locale', function (string $locale, string $translation): void {
    setCustomLocale();

    $foo = 'test';

    getJson(route('via.parameter', ['key' => $foo, 'locale' => $locale]))
        ->assertSuccessful()
        ->assertJsonPath($foo, $translation);

    assertEventDispatched();
})->with('direct locales');

test('aliased locale', function (string $locale): void {
    setCustomLocale();

    $foo = 'test';

    getJson(route('via.parameter', ['key' => $foo, 'locale' => $locale]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventDispatched();
})->with('aliased locales');

test('empty locale', function (int|string|null $locale): void {
    $foo = 'test';

    getJson(route('via.parameter', ['key' => $foo, 'locale' => $locale]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
})->with('incorrect locales');

test('uninstalled locale', function (string $locale): void {
    $foo = 'test';

    getJson(route('via.parameter', ['key' => $foo, 'locale' => $locale]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
})->with('uninstalled locales');

test('unknown locale', function (int|string $locale): void {
    $foo = 'test';

    getJson(route('via.parameter', ['key' => $foo, 'locale' => $locale]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
})->with('unknown locales');
