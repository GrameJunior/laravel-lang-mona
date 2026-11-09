<?php

declare(strict_types=1);

use Workbench\App\Constants\FakeLocale;
use Workbench\App\Constants\FakeTranslation;

use function Pest\Laravel\getJson;

test('main locale', function (string $locale) {
    $foo = 'test';

    getJson(route('via.parameter.redirect', compact('foo', 'locale')))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventDispatched();
})->with('main locales');

test('aliased locale', function (string $locale) {
    $foo = 'test';

    getJson(route('via.parameter.redirect', compact('foo', 'locale')))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::German);

    assertEventDispatched();
})->with('aliased locales');

test('empty locale', function (int|string|null $locale) {
    $foo = 'test';

    getJson(route('via.parameter.redirect', compact('foo', 'locale')))
        ->assertRedirectToRoute('via.parameter.redirect', [
            'locale' => FakeLocale::Default,
            'foo'    => $foo,
        ]);

    assertEventNotDispatched();
})->with('incorrect locales');

test('uninstalled locale', function (string $locale) {
    $foo = 'test';

    getJson(route('via.parameter.redirect', compact('foo', 'locale')))
        ->assertRedirectToRoute('via.parameter.redirect', [
            'locale' => FakeLocale::Default,
            'foo'    => $foo,
        ]);

    assertEventNotDispatched();
})->with('uninstalled locales');

test('unknown locale', function (int|string $locale) {
    $foo = 'test';

    getJson(route('via.parameter.redirect', compact('foo', 'locale')))
        ->assertRedirectToRoute('via.parameter.redirect', [
            'locale' => FakeLocale::Default,
            'foo'    => $foo,
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
