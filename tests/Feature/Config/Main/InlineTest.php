<?php

declare(strict_types=1);

use LaravelLang\Config\Enums\Name;

test('inline', function (bool $is) {
    setConfig(Name::Main, 'inline', $is);

    expect(getConfig()->main->inline)->toBe($is);
})->with('boolean');
