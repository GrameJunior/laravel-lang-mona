<?php

declare(strict_types=1);

use Workbench\App\Constants\FakeTranslation;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

test('main locale', function () {
    actingAs(fakeUser());

    $foo = 'test';

    getJson(route('via.model.default', ['key' => $foo]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
});

test('aliased locale', function (string $locale) {
    actingAs(fakeUser($locale));

    $foo = 'test';

    getJson(route('via.model.default', compact('foo', 'locale')))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::German);

    assertEventDispatched();
})->with('aliased locales');

test('empty locale', function (int|string|null $locale) {
    actingAs(fakeUser($locale));

    $foo = 'test';

    getJson(route('via.model.default', compact('foo', 'locale')))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
})->with('incorrect locales');

test('uninstalled locale', function (string $locale) {
    actingAs(fakeUser($locale));

    $foo = 'test';

    getJson(route('via.model.default', compact('foo', 'locale')))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventDispatched();
})->with('uninstalled locales');

test('unknown locale', function (int|string $locale) {
    actingAs(fakeUser($locale));

    $foo = 'test';

    getJson(route('via.model.default', compact('foo', 'locale')))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventDispatched();
})->with('unknown locales');
