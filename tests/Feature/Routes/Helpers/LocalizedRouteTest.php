<?php

declare(strict_types=1);

use LaravelLang\Config\Enums\Name;
use LaravelLang\Config\Facades\Config;
use Workbench\App\Constants\FakeLocale;

test('route groups', function () {
    $name        = Config::shared()->routes->namePrefix;
    $locale      = FakeLocale::Default;
    $localeAlias = FakeLocale::DefaultAlias;

    expect(localizedRoute($name . 'via.group.facade', ['key' => 'bar', 'locale' => $locale]))
        ->toEndWith("localhost/$locale/group/facade/bar");

    expect(localizedRoute($name . 'via.group.macro', ['key' => 'bar', 'locale' => $localeAlias]))
        ->toEndWith("localhost/$localeAlias/group/macro/bar");

    expect(localizedRoute('via.group.facade', ['key' => 'bar', 'locale' => $locale]))
        ->toEndWith("localhost/$locale/group/facade/bar");

    expect(localizedRoute('via.group.macro', ['key' => 'bar', 'locale' => $localeAlias]))
        ->toEndWith("localhost/$localeAlias/group/macro/bar");
});

test('routes without groups', function () {
    $locale = FakeLocale::Default;

    expect(localizedRoute('via.model.default', ['key' => 'bar', 'locale' => $locale]))
        ->toEndWith('localhost/model/default/bar?locale=' . $locale);
});

test('routes hide fallback', function (bool $set) {
    $locale   = FakeLocale::Default;
    $fallback = FakeLocale::Default;

    config()->set(Name::Shared() . '.routes.hide_default', true);
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

    config()->set(Name::Shared() . '.routes.hide_default', true);

    expect(localizedRoute('via.parameter', ['key' => 'bar', 'locale' => $locale]))
        ->toEndWith("localhost/path/bar/$locale");

    expect(localizedRoute('via.parameter', ['key' => 'bar', 'locale' => $fallback]))
        ->toEndWith('localhost/path/bar');
});

test('routes does not hide', function () {
    $fallback = FakeLocale::Fallback;

    config()->set(Name::Shared() . '.routes.hide_default', true);

    expect(localizedRoute('via.parameter', ['key' => 'bar']))
        ->toEndWith('localhost/path/bar');
});
