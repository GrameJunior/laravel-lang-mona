<?php

declare(strict_types=1);

use LaravelLang\Config\Enums\Name;

test('hide', function (bool $is) {
    setConfig(Name::Routes, 'hide_default', $is);

    expect(getConfig()->route->hideDefault)->toBe($is);
})->with('boolean');
