<?php

declare(strict_types=1);

use LaravelLang\Config\Enums\Name;

test('enabled', function (bool $is) {
    setConfig(Name::Punctuation, 'enabled', $is);

    expect(getConfig()->punctuation->enabled)->toBe($is);
})->with('boolean');
