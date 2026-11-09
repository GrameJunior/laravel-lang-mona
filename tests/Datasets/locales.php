<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use Workbench\App\Constants\FakeLocale;
use Workbench\App\Constants\FakeTranslation;

dataset('incorrect locales', [
    FakeLocale::Incorrect1,
    '0',
    '',
    null,
]);

dataset('correct locales', array_map(static fn (Locale $locale): array => [$locale], Locale::cases()));

dataset('direct locales', [
    FakeLocale::Default  => [FakeLocale::Default, FakeTranslation::French],
    FakeLocale::Fallback => [FakeLocale::Fallback, FakeTranslation::German],
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
    123,
]);
