<?php

declare(strict_types=1);

use LaravelLang\Config\Enums\Name;

test('directory', function (string $value) {
    setConfig(Name::Model, 'directory', $value);

    expect(getConfig()->model->directory)->toBe($value);
})->with('foo bar');
