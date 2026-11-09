<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use Workbench\App\Constants\FakeLocale;

dataset('incorrect locales', [
    FakeLocale::Incorrect1,
    '0',
    '',
    null,
]);

dataset('correct locales', array_map(static fn (Locale $locale): array => [$locale], Locale::cases()));

dataset('main locales', [
    FakeLocale::Fallback,
]);

dataset('aliased locales', [
    FakeLocale::Default,
    FakeLocale::DefaultAlias,
]);

dataset('uninstalled locales', [
    Locale::Assamese->value,
    Locale::NorwegianBokmal->value,
    Locale::Bulgarian->value,
]);

dataset('unknown locales', [
    'foo',
    'bar',
    'qwerty',
    'en_US',
    123,
]);
