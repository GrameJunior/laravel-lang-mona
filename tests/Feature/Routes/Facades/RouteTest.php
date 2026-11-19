<?php

declare(strict_types=1);

use LaravelLang\Config\Services\RouteLangConfig;
use LaravelLang\LocaleList\Locale;

test('group', function (string $routeName, string $uri) {
    expect(route($routeName, ['key' => 'test']))
        ->toBeString()
        ->toBe('http://localhost/' . $uri);

    expect(route(app(RouteLangConfig::class)->prefix . $routeName, [
        'locale' => Locale::French->value,
        'key'    => 'test',
    ]))->toBeString()->toBe('http://localhost/fr/' . $uri);
})->with([
    'facade' => ['via.group.facade', 'group/facade/test'],
    'macro'  => ['via.group.macro', 'group/macro/test'],
]);
