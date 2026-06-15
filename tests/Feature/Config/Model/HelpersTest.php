<?php

declare(strict_types=1);

use LaravelLang\Config\Enums\Name;

test('helpers', function () {
    setConfig(Name::Model, 'helpers', '/foo/bar');

    expect(getConfig()->model->helpers)->toBe('/foo/bar');
});
