<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use LaravelLang\Config\Services\RouteLangConfig;
use LaravelLang\Routes\Facades\LocalizationRoute;
use LaravelLang\Routes\Services\RouteService;
use Workbench\App\Constants\FakeLocale;

use function LaravelLang\Routes\localizedRoute;
use function Pest\Laravel\get;

test('route groups', function (): void {
    $name     = app(RouteLangConfig::class)->prefix;
    $locale   = FakeLocale::Default;
    $fallback = FakeLocale::Fallback;

    expect(localizedRoute($name . 'via.group.facade', ['key' => 'bar', 'locale' => $locale]))
        ->toEndWith("localhost/$locale/group/facade/bar");

    expect(localizedRoute($name . 'via.group.macro', ['key' => 'bar', 'locale' => $fallback]))
        ->toEndWith("localhost/$fallback/group/macro/bar");

    expect(localizedRoute('via.group.facade', ['key' => 'bar', 'locale' => $locale]))
        ->toEndWith("localhost/$locale/group/facade/bar");

    expect(localizedRoute('via.group.macro', ['key' => 'bar', 'locale' => $fallback]))
        ->toEndWith("localhost/$fallback/group/macro/bar");
});

test('routes without groups', function (): void {
    $locale = FakeLocale::Default;

    expect(localizedRoute('via.model.default', ['key' => 'bar', 'locale' => $locale]))
        ->toEndWith('localhost/model/default/bar?locale=' . $locale);
});

test('routes hide fallback', function (bool $set): void {
    $locale   = FakeLocale::Default;
    $fallback = FakeLocale::Default;

    config()->set('localization_routes.hide', true);
    config()->set('app.fallback_locale', $fallback);

    $params = $set
        ? ['key' => 'bar', 'locale' => $locale]
        : ['key' => 'bar'];

    expect(localizedRoute('via.parameter', $params))
        ->toEndWith('localhost/path/bar');
})->with([true, false]);

test('routes hide manual', function (): void {
    $locale   = FakeLocale::Default;
    $fallback = FakeLocale::Fallback;

    config()->set('localization_routes.hide', true);
    config()->set('app.fallback_locale', $fallback);

    expect(localizedRoute('via.parameter', ['key' => 'bar', 'locale' => $locale]))
        ->toEndWith("localhost/path/bar/$locale");

    expect(localizedRoute('via.parameter', ['key' => 'bar', 'locale' => $fallback]))
        ->toEndWith('localhost/path/bar');
});

test('routes does not hide', function (): void {
    $fallback = FakeLocale::Fallback;

    config()->set('localization_routes.hide', true);
    config()->set('app.fallback_locale', $fallback);

    expect(localizedRoute('via.parameter', ['key' => 'bar']))
        ->toEndWith('localhost/path/bar');
});

test('uses the configured parameter and preserves the remaining route parameters on redirect', function (): void {
    config()->set('localization_routes.names.parameter', 'language');
    LocalizationRoute::clearResolvedInstance(RouteService::class);

    Route::localizedGroup(function (): void {
        Route::get('catalog/{category}/{item}', fn () => app()->getLocale())
            ->name('catalog.item');
    });
    Route::getRoutes()->refreshNameLookups();

    $url = localizedRoute('catalog.item', [
        'language' => 'invalid',
        'category' => 'books',
        'item'     => 42,
    ], absolute: false);

    expect($url)->toBe('/invalid/catalog/books/42');

    get($url)->assertRedirect('/catalog/books/42');
    get('/catalog/books/42')->assertOk()->assertSee('fr');
});

test('keeps the localized route helper usable when its file is loaded again', function (): void {
    require dirname(__DIR__, 4) . '/src/Routes/src/helpers.php';

    expect(localizedRoute('via.group.macro', ['key' => 'bar', 'locale' => 'de'], absolute: false))
        ->toBe('/de/group/macro/bar');
});
