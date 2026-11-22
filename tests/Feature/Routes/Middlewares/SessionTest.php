<?php

declare(strict_types=1);

use LaravelLang\Config\Services\RouteNameLangConfig;
use LaravelLang\Exceptions\InvalidLocaleInputException;
use Workbench\App\Constants\FakeTranslation;

use function Pest\Laravel\withoutExceptionHandling;
use function Pest\Laravel\withSession;

test('main locale', function (string $locale, string $translation): void {
    setCustomLocale();

    $foo = 'test';

    withSession([app(RouteNameLangConfig::class)->session => $locale])
        ->getJson(route('via.session', ['key' => $foo]))
        ->assertSuccessful()
        ->assertJsonPath($foo, $translation);

    assertEventDispatched();
})->with('direct locales');

test('aliased locale', function (string $locale): void {
    setCustomLocale();

    $foo = 'test';

    withSession([app(RouteNameLangConfig::class)->session => $locale])
        ->getJson(route('via.session', ['key' => $foo]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventDispatched();
})->with('aliased locales');

test('empty locale', function (int|string|null $locale): void {
    $foo = 'test';

    withSession([app(RouteNameLangConfig::class)->session => $locale])
        ->getJson(route('via.session', ['key' => $foo]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
})->with('incorrect locales');

test('uninstalled locale', function (string $locale): void {
    $foo = 'test';

    withSession([app(RouteNameLangConfig::class)->session => $locale])
        ->getJson(route('via.session', ['key' => $foo]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
})->with('uninstalled locales');

test('unknown locale', function (int|string $locale): void {
    $foo = 'test';

    withSession([app(RouteNameLangConfig::class)->session => $locale])
        ->getJson(route('via.session', ['key' => $foo]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
})->with('unknown locales');

test('rejects a non-scalar session locale', function (): void {
    withoutExceptionHandling();

    $request = withSession([app(RouteNameLangConfig::class)->session => ['fr']]);

    expect(fn () => $request->getJson(route('via.session', ['key' => 'test'])))
        ->toThrow(InvalidLocaleInputException::class, 'The session locale must be an integer, a string, or null');
});
