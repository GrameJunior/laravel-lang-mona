<?php

declare(strict_types=1);

use LaravelLang\Config\Enums\Name;

test('enabled filter', function (bool $is) {
    setConfig(Name::Model, 'filter_enabled', $is);

    expect(getConfig()->model->filterEnabled)->toBe($is);
})->with('boolean');
