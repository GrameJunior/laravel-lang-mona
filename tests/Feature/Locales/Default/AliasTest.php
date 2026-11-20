<?php

declare(strict_types=1);

use LaravelLang\Locales\Facades\Locales;

test('returns no locale code or alias for an empty string', function () {
    expect(Locales::fromAlias(''))->toBeNull()
        ->and(Locales::toAlias(''))->toBeNull();
});
