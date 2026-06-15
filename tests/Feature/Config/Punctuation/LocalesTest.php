<?php

declare(strict_types=1);

use LaravelLang\Config\Enums\Name;
use LaravelLang\LocaleList\Locale;

beforeEach(fn () => setConfig(Name::Punctuation, 'common', [
    'double_quote_opener' => 'q1',
    'double_quote_closer' => 'q2',
    'single_quote_opener' => 'q3',
    'single_quote_closer' => 'q4',
]));

test('filled', function () {
    setConfig(Name::Punctuation, 'locales.' . Locale::German->value, [
        'double_quote_opener' => 'w1',
        'double_quote_closer' => 'w2',
        'single_quote_opener' => 'w3',
        'single_quote_closer' => 'w4',
    ]);

    expect(getConfig()->punctuation->locales[Locale::German->value])
        ->doubleOpener->toBe('w1')
        ->doubleCloser->toBe('w2')
        ->singleOpener->toBe('w3')
        ->singleCloser->toBe('w4');
});

test('partial', function () {
    setConfig(Name::Punctuation, 'locales.' . Locale::German->value, [
        'double_quote_opener' => 'w1',
        'double_quote_closer' => 'w2',
    ]);

    expect(getConfig()->punctuation->locales[Locale::German->value])
        ->doubleOpener->toBe('w1')
        ->doubleCloser->toBe('w2')
        ->singleOpener->toBe('q3')
        ->singleCloser->toBe('q4');
});
