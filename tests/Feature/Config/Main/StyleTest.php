<?php

declare(strict_types=1);

use LaravelLang\Config\Enums\Name;

test('style', function (string $name) {
    setConfig(Name::Main, 'style', $name);

    expect(getConfig()->main->style)->toBe($name);
})->with(['formal', 'informal']);
