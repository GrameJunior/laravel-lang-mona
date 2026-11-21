<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use LaravelLang\LocaleList\Locale;
use LaravelLang\Routes\Middlewares\LocalizationByParameterMiddleware;
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

test('uses a locale bound to a locale enum', function (): void {
    Route::bind('locale', static fn (string $value): Locale => Locale::from($value));
    Route::middleware(['web', LocalizationByParameterMiddleware::class])
        ->get('bound-locale/{locale}', fn (): array => ['locale' => app()->getLocale()]);

    getJson('/bound-locale/en')->assertOk()->assertJsonPath('locale', 'en');

    assertEventDispatched();
});

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
