<?php

declare(strict_types=1);

use LaravelLang\Config\Enums\Name;

test('names', function () {
    setConfig(Name::Routes, 'names', [
        'column'    => 'q1',
        'cookie'    => 'q2',
        'header'    => 'q3',
        'parameter' => 'q4',
        'session'   => 'q5',
    ]);

    expect(getConfig()->route->name)
        ->column->toBe('q1')
        ->cookie->toBe('q2')
        ->header->toBe('q3')
        ->parameter->toBe('q4')
        ->session->toBe('q5');
});
