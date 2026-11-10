<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\Publisher\Console\Remove;

use LaravelLang\Publisher\Exceptions\ProtectedLocaleException;

use LaravelLang\Publisher\Exceptions\UnknownLocaleCodeException;
use Workbench\App\Constants\FakeLocale;

use function Pest\Laravel\artisan;

test('remove locale', function (): void {
    expect(lang_path('de'))->toBeReadableDirectory()->toBeWritableDirectory();
    expect(lang_path(Workbench\App\Constants\FakeLocale::English))->toBeReadableDirectory()->toBeWritableDirectory();
    expect(lang_path('fr'))->toBeReadableDirectory()->toBeWritableDirectory();

    expect(lang_path('de.json'))->toBeReadableFile()->toBeWritableFile();
    expect(lang_path('en.json'))->toBeReadableFile()->toBeWritableFile();
    expect(lang_path('fr.json'))->toBeReadableFile()->toBeWritableFile();

    artisan(Remove::class, [
        'locales' => [Workbench\App\Constants\FakeLocale::English],
    ])->assertSuccessful()->run();

    expect(lang_path('de'))->toBeReadableDirectory()->toBeWritableDirectory();
    expect(
        lang_path(Workbench\App\Constants\FakeLocale::English)
    )->not->toBeReadableDirectory()->not->toBeWritableDirectory();
    expect(lang_path('fr'))->toBeReadableDirectory()->toBeWritableDirectory();

    expect(lang_path('de.json'))->toBeReadableFile()->toBeWritableFile();
    expect(lang_path('en.json'))->not->toBeReadableFile()->not->toBeWritableFile();
    expect(lang_path('fr.json'))->toBeReadableFile()->toBeWritableFile();
});

test('remove protected locales', function (): void {
    expect(lang_path('de'))->toBeReadableDirectory()->toBeWritableDirectory();
    expect(lang_path(Workbench\App\Constants\FakeLocale::English))->toBeReadableDirectory()->toBeWritableDirectory();
    expect(lang_path('fr'))->toBeReadableDirectory()->toBeWritableDirectory();

    expect(lang_path('de.json'))->toBeReadableFile()->toBeWritableFile();
    expect(lang_path('en.json'))->toBeReadableFile()->toBeWritableFile();
    expect(lang_path('fr.json'))->toBeReadableFile()->toBeWritableFile();

    artisan(Remove::class, [
        'locales' => [app()->getLocale(), app()->getFallbackLocale()],
    ])->run();
})->throws(ProtectedLocaleException::class);

test('remove uninstalled locales', function (): void {
    $locale = Locale::Tagalog->value;

    expect(lang_path($locale))->not->toBeReadableDirectory();
    expect(lang_path($locale . '.json'))->not->toBeReadableFile();

    artisan(Remove::class, [
        'locales' => [$locale],
    ])->assertSuccessful()->run();

    expect(lang_path($locale))->not->toBeReadableDirectory();
    expect(lang_path($locale . '.json'))->not->toBeReadableFile();
});

test('aliased locales', function (string $raw, string $alias): void {
    ensureLang($raw);

    expect(lang_path($raw))->toBeReadableDirectory();

    artisan(Remove::class, [
        'locales' => [$alias],
    ])->assertSuccessful()->run();

    expect(lang_path($raw))->not->toBeReadableDirectory();
    expect(lang_path($alias))->not->toBeReadableDirectory();

    expect(lang_path($raw . '.json'))->not->toBeReadableFile();
    expect(lang_path($alias . '.json'))->not->toBeReadableFile();
})->with([
    'direct'  => [FakeLocale::Italian, FakeLocale::Italian],
    'aliased' => [FakeLocale::Italian, FakeLocale::ItalianAlias],
]);

test('all', function (): void {
    $protected = [
        FakeLocale::Default,
        FakeLocale::Fallback,
    ];

    foreach (Locale::values() as $locale) {
        ensureLang($locale);
    }

    artisan(Remove::class)
        ->expectsConfirmation('Do you want to remove all localizations?', 'yes')
        ->assertSuccessful()
        ->run();

    foreach (Locale::values() as $locale) {
        in_array($locale, $protected, true)
            ? expect(lang_path($locale))->toBeReadableDirectory()
            : expect(lang_path($locale))->not->toBeReadableDirectory();
    }
});

test('with protected', function (): void {
    foreach (Locale::values() as $locale) {
        ensureLang($locale);
    }

    artisan(Remove::class, [
        '--force' => true,
    ])
        ->expectsConfirmation('Do you want to remove all localizations?', 'yes')
        ->assertSuccessful()
        ->run();

    foreach (Locale::values() as $locale) {
        expect(lang_path($locale))->not->toBeReadableDirectory();
    }
});

test('unknown locale', function (): void {
    artisan(Remove::class, [
        'locales' => [FakeLocale::Incorrect1],
    ])->run();
})->throws(UnknownLocaleCodeException::class);
