<?php

declare(strict_types=1);

use LaravelLang\Config\Services\RouteLangConfig;
use Workbench\App\Constants\FakeLocale;

use function LaravelLang\Routes\localizedRoute;

test('route groups', function () {
    $name     = app(RouteLangConfig::class)->prefix;
    $locale   = FakeLocale::Default;
    $fallback = FakeLocale::Fallback;

    expect(LaravelLang\Routes\localizedRoute($name . 'via.group.facade', ['key' => 'bar', 'locale' => $locale]))
        ->toEndWith("localhost/$locale/group/facade/bar");

    expect(LaravelLang\Routes\localizedRoute($name . 'via.group.macro', ['key' => 'bar', 'locale' => $fallback]))
        ->toEndWith("localhost/$fallback/group/macro/bar");

    expect(LaravelLang\Routes\localizedRoute('via.group.facade', ['key' => 'bar', 'locale' => $locale]))
        ->toEndWith("localhost/$locale/group/facade/bar");

    expect(LaravelLang\Routes\localizedRoute('via.group.macro', ['key' => 'bar', 'locale' => $fallback]))
        ->toEndWith("localhost/$fallback/group/macro/bar");
});

test('routes without groups', function () {
    $locale = FakeLocale::Default;

    expect(LaravelLang\Routes\localizedRoute('via.model.default', ['key' => 'bar', 'locale' => $locale]))
        ->toEndWith('localhost/model/default/bar?locale=' . $locale);
});

test('routes hide fallback', function (bool $set) {
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

test('routes hide manual', function () {
    $locale   = FakeLocale::Default;
    $fallback = FakeLocale::Fallback;

    config()->set('localization_routes.hide', true);
    config()->set('app.fallback_locale', $fallback);

    expect(LaravelLang\Routes\localizedRoute('via.parameter', ['key' => 'bar', 'locale' => $locale]))
        ->toEndWith("localhost/path/bar/$locale");

    expect(LaravelLang\Routes\localizedRoute('via.parameter', ['key' => 'bar', 'locale' => $fallback]))
        ->toEndWith('localhost/path/bar');
});

test('routes does not hide', function () {
    $fallback = FakeLocale::Fallback;

    config()->set('localization_routes.hide', true);
    config()->set('app.fallback_locale', $fallback);

    expect(LaravelLang\Routes\localizedRoute('via.parameter', ['key' => 'bar']))
        ->toEndWith('localhost/path/bar');
});
