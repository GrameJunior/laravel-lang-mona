<?php

declare(strict_types=1);

use LaravelLang\Config\Enums\Name;

test('prefix', function () {
    setConfig(Name::Routes, 'name_prefix', 'qwerty');

    expect(getConfig()->route->namePrefix)->toBe('qwerty');
});
