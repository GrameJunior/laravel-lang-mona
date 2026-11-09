<?php

declare(strict_types=1);

use Workbench\App\Constants\FakeLocale;
use Workbench\App\Constants\FakeTranslation;

use function Pest\Laravel\getJson;

test('main locale', function (string $locale, string $translation) {
    setCustomLocale();

    $foo = 'test';

    getJson(route('via.parameter.redirect', ['key' => $foo, 'locale' => $locale]))
        ->assertSuccessful()
        ->assertJsonPath($foo, $translation);

    assertEventDispatched();
})->with('direct locales');

test('aliased locale', function (string $locale) {
    $foo = 'test';

    setCustomLocale();

    getJson(route('via.parameter.redirect', ['key' => $foo, 'locale' => $locale]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventDispatched();
})->with('aliased locales');

test('empty locale', function (int|string|null $locale) {
    $foo = 'test';

    getJson(route('via.parameter.redirect', ['key' => $foo, 'locale' => $locale]))
        ->assertRedirectToRoute('via.parameter.redirect', [
            'locale' => FakeLocale::Default,
            'key'    => $foo,
        ]);

    assertEventNotDispatched();
})->with('incorrect locales');

test('uninstalled locale', function (string $locale) {
    $foo = 'test';

    getJson(route('via.parameter.redirect', ['key' => $foo, 'locale' => $locale]))
        ->assertRedirectToRoute('via.parameter.redirect', [
            'locale' => FakeLocale::Default,
            'key'    => $foo,
        ]);

    assertEventNotDispatched();
})->with('uninstalled locales');

test('unknown locale', function (int|string $locale) {
    $foo = 'test';

    getJson(route('via.parameter.redirect', ['key' => $foo, 'locale' => $locale]))
        ->assertRedirectToRoute('via.parameter.redirect', [
            'locale' => FakeLocale::Default,
            'key'    => $foo,
        ]);

    assertEventNotDispatched();
})->with('unknown locales');

test('not named', function (int|string|null $locale) {
    $foo = 'test';

    getJson(url('not-named/redirect/' . $foo . '/' . $locale))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
})->with('incorrect locales');
