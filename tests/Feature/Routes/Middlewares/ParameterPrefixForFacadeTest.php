<?php

declare(strict_types=1);

use LaravelLang\Config\Enums\Name;
use LaravelLang\Routes\Helpers\Route as RouteName;

use Workbench\App\Constants\FakeTranslation;

use function Pest\Laravel\getJson;

test('main without prefix', function () {
    $foo = 'test';

    getJson(route('via.group.facade', ['key' => $foo]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);
});

test('main locale with enabled redirect', function (string $locale) {
    config()->set(Name::Shared() . '.routes.redirect_default', true);

    $foo = 'test';

    getJson(route(RouteName::prefix() . 'via.group.facade', ['key' => $foo, 'locale' => $locale]))
        ->assertRedirectToRoute('via.group.facade', ['key' => $foo]);

    assertEventNotDispatched();
})->with('aliased locales');

test('main locale with disabled redirect', function (string $locale, string $translation) {
    config()->set(Name::Shared() . '.routes.redirect_default', false);

    setCustomLocale();

    $foo = 'test';

    getJson(route(RouteName::prefix() . 'via.group.facade', ['key' => $foo, 'locale' => $locale]))
        ->assertSuccessful()
        ->assertJsonPath($foo, $translation);

    assertEventDispatched();
})->with('direct locales');

test('aliased locale', function (string $locale) {
    $foo = 'test';

    setCustomLocale();

    getJson(route(RouteName::prefix() . 'via.group.facade', ['key' => $foo, 'locale' => $locale]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventDispatched();
})->with('aliased locales');

test('uninstalled locale', function (string $locale) {
    $foo = 'test';

    setCustomLocale();

    getJson(route(RouteName::prefix() . 'via.group.facade', ['key' => $foo, 'locale' => $locale]))
        ->assertRedirectToRoute('via.group.facade', ['key' => $foo]);

    assertEventNotDispatched();
})->with('uninstalled locales');

test('unknown locale', function (int|string $locale) {
    $foo = 'test';

    setCustomLocale();

    getJson(route(RouteName::prefix() . 'via.group.facade', ['key' => $foo, 'locale' => $locale]))
        ->assertRedirectToRoute('via.group.facade', ['key' => $foo]);

    assertEventNotDispatched();
})->with('unknown locales');
