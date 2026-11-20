<?php

declare(strict_types=1);

use Workbench\App\Constants\FakeLocale;
use Workbench\App\Constants\FakeTranslation;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

test('main locale', function (): void {
    actingAs(fakeUser(FakeLocale::Fallback));

    $foo = 'test';

    getJson(route('via.model.default', ['key' => $foo]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::German);

    assertEventDispatched();
});

test('aliased locale', function (string $locale): void {
    actingAs(fakeUser($locale));

    setCustomLocale();

    $foo = 'test';

    getJson(route('via.model.default', ['key' => $foo, 'locale' => $locale]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventDispatched();
})->with('aliased locales');

test('empty locale', function (int|string|null $locale): void {
    actingAs(fakeUser($locale));

    $foo = 'test';

    getJson(route('via.model.default', ['key' => $foo, 'locale' => $locale]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
})->with('incorrect locales');

test('uninstalled locale', function (string $locale): void {
    actingAs(fakeUser($locale));

    $foo = 'test';

    getJson(route('via.model.default', ['key' => $foo, 'locale' => $locale]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
})->with('uninstalled locales');

test('unknown locale', function (int|string $locale): void {
    actingAs(fakeUser($locale));

    $foo = 'test';

    getJson(route('via.model.default', ['key' => $foo, 'locale' => $locale]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
})->with('unknown locales');
