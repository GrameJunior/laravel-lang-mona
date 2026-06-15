<?php

declare(strict_types=1);

use LaravelLang\Config\Enums\Name;

test('redirect', function (bool $is) {
    setConfig(Name::Routes, 'redirect_default', $is);

    expect(getConfig()->route->redirectDefault)->toBe($is);
})->with('boolean');
