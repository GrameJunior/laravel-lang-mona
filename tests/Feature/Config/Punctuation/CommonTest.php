<?php

declare(strict_types=1);

use LaravelLang\Config\Enums\Name;

test('common', function () {
    setConfig(Name::Punctuation, 'common', [
        'double_quote_opener' => 'q1',
        'double_quote_closer' => 'q2',
        'single_quote_opener' => 'q3',
        'single_quote_closer' => 'q4',
    ]);

    expect(getConfig()->punctuation->common)
        ->doubleOpener->toBe('q1')
        ->doubleCloser->toBe('q2')
        ->singleOpener->toBe('q3')
        ->singleCloser->toBe('q4');
});
