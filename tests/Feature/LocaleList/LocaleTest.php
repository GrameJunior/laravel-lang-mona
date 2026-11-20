<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;

test('returns locale enum cases when values are disabled', function () {
    expect(Locale::collection(values: false)->all())->toBe(Locale::cases());
});
