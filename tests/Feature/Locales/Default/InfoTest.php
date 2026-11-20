<?php

declare(strict_types=1);

use LaravelLang\Exceptions\UnknownLocaleException;
use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test(
    'installed',
    function (Locale|string|null $locale, string $code, string $alias, string $native, string $localized): void {
        expect(Locales::info($locale))
            ->code->toBe($code)
            ->alias->toBe($alias)
            ->native->toBe($native)
            ->localized->toBe($localized);
    }
)->with([
    'English' => [Locale::English, FakeLocale::English, FakeLocale::English, 'English', 'Anglais'],
    'German'  => [Locale::German, FakeLocale::Fallback, FakeLocale::Fallback, 'Deutsch', 'Allemand'],
    'French'  => [Locale::French, FakeLocale::Default, FakeLocale::DefaultAlias, 'Français', 'Français'],

    'French string' => [Locale::French->value, FakeLocale::Default, FakeLocale::DefaultAlias, 'Français', 'Français'],

    'null' => [Locale::French, FakeLocale::Default, FakeLocale::DefaultAlias, 'Français', 'Français'],
]);

test(
    'uninstalled',
    function (Locale|string|null $locale, string $code, string $alias, string $native, string $localized): void {
        expect(Locales::info($locale))
            ->code->toBe($code)
            ->alias->toBe($alias)
            ->native->toBe($native)
            ->localized->toBe($localized);
    }
)->with([
    'data'   => [Locale::Swedish, FakeLocale::Uninstalled, FakeLocale::Uninstalled, 'Svenska', 'Suédois'],
    'string' => [Locale::Swedish->value, FakeLocale::Uninstalled, FakeLocale::Uninstalled, 'Svenska', 'Suédois'],
]);

test('incorrect', function (Locale|string $locale): void {
    Locales::info($locale);
})->with([
    'data'   => [FakeLocale::Incorrect1],
    'string' => [FakeLocale::Incorrect2],
])->throws(UnknownLocaleException::class);
