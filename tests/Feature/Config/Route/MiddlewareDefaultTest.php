<?php

declare(strict_types=1);

use LaravelLang\Config\Enums\Name;

test('middleware', function () {
    setConfig(Name::Routes, 'middlewares.default', ['foo', 'bar']);
    setConfig(Name::Routes, 'middlewares.prefix', ['qwe', 'rty']);

    expect(getConfig()->route->middleware->default)->toBe(['foo', 'bar']);
});
