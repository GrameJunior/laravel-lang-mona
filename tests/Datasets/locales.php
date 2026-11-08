<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;

dataset('incorrect locales', [
    'foo',
    '0',
    null,
]);

dataset('correct locales', array_map(static fn (Locale $locale): array => [$locale], Locale::cases()));
