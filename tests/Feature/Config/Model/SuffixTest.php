<?php

declare(strict_types=1);

use LaravelLang\Config\Enums\Name;

test('suffix', function (string $value) {
    setConfig(Name::Model, 'suffix', $value);

    expect(getConfig()->model->suffix)->toBe($value);
})->with('foo bar');
