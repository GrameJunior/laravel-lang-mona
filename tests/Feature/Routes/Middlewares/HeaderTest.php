<?php

declare(strict_types=1);

use LaravelLang\Config\Services\RouteNameLangConfig;
use LaravelLang\Exceptions\UnknownLocaleException;
use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeTranslation;

use function Pest\Laravel\getJson;

test('direct locale', function (string $locale, string $translated) {
    $foo = 'test';

    setCustomLocale();

    getJson(route('via.header', ['key' => $foo]), [
        app(RouteNameLangConfig::class)->header => $locale,
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, $translated);

    assertEventDispatched();
})->with('direct locales');

test('aliased locale', function (string $locale) {
    $foo = 'test';

    setCustomLocale();

    getJson(route('via.header', ['key' => $foo]), [
        app(RouteNameLangConfig::class)->header => $locale,
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventDispatched();
})->with('aliased locales');

test('empty locale', function (int|string|null $locale) {
    $foo = 'test';

    getJson(route('via.header', ['key' => $foo]), [
        app(RouteNameLangConfig::class)->header => $locale,
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
})->with('incorrect locales');

test('uninstalled locale', function (string $locale) {
    $foo = 'test';

    getJson(route('via.header', ['key' => $foo]), [
        app(RouteNameLangConfig::class)->header => $locale,
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
})->with('uninstalled locales');

test('unknown locale', function (int|string $locale) {
    $foo = 'test';

    getJson(route('via.header', ['key' => $foo]), [
        app(RouteNameLangConfig::class)->header => $locale,
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
})->with('unknown locales');

test('uses the fallback when an installed locale cannot be resolved', function () {
    $locales = Mockery::mock(Locales::getFacadeRoot());
    $locales->shouldReceive('info')->once()->with('en')->andThrow(new UnknownLocaleException('en'));
    Locales::swap($locales);

    getJson(route('via.header', ['key' => 'test']), ['Accept-Language' => 'en'])
        ->assertOk()
        ->assertJsonPath('test', FakeTranslation::German);

    expect(app()->getLocale())->toBe('de');
    assertEventNotDispatched();
});

test('multiple accept-language', function (string $locale) {
    $foo = 'test';

    setCustomLocale();

    getJson(route('via.header', ['key' => $foo]), [
        app(RouteNameLangConfig::class)->header => $locale,
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventDispatched();
})->with('locale headers');

test('custom header', function (string $locale) {
    config(['localization_routes.names.header' => 'X-Custom-Lang']);

    $foo = 'test';

    setCustomLocale();

    getJson(route('via.header', ['key' => $foo]), [
        'X-Custom-Lang' => $locale,
    ])
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventDispatched();
})->with('locale headers');
