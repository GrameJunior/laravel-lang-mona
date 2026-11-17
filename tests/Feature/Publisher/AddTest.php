<?php

declare(strict_types=1);

use LaravelLang\Exceptions\UnknownLocaleCodeException;
use LaravelLang\LocaleList\Locale;
use LaravelLang\Publisher\Console\AddCommand;
use Workbench\App\Constants\FakeLocale;

use function Pest\Laravel\artisan;

test('add locale', function (array $config, array $foo, array $bar, array $baz): void {
    config()->set('localization_main.styles', $config);

    $locale = Locale::Swedish->value;

    expect(__('auth.foo'))->toBe('auth.foo');
    expect(__('bar.bar'))->toBe('bar.bar');
    expect(__('baz'))->toBe('baz');

    expect(__('Foo'))->toBe('Toutes les données ont été chargées.');

    artisan(AddCommand::class, [
        'locales' => [$locale],
    ])->assertSuccessful()->run();

    app()->setlocale($locale);

    expect(__('auth.foo'))->toBe($foo[1]);
    expect(__('bar.bar'))->toBe($bar[1]);
    expect(__('baz'))->toBe($baz[1]);

    expect(__('Foo'))->toBe('Alle Ressourcen geladen.');
})->with('translations');

test('all - yes', function () {
    artisan(AddCommand::class)
        ->expectsConfirmation('Do you want to install all localizations?', 'yes')
        ->assertSuccessful()
        ->run();

    foreach (Locale::values() as $locale) {
        expect(lang_path($locale))->toBeReadableDirectory();
    }
});

test('all - no', function () {
    $installed = [
        FakeLocale::Default,
        FakeLocale::Fallback,
        FakeLocale::English,
    ];

    artisan(AddCommand::class)
        ->expectsConfirmation('Do you want to install all localizations?')
        ->assertSuccessful()
        ->run();

    foreach (Locale::values() as $locale) {
        in_array($locale, $installed, true)
            ? expect(lang_path($locale))->toBeReadableDirectory()
            : expect(lang_path($locale))->not->toBeReadableDirectory();
    }
});

test('unknown locale', function () {
    artisan(AddCommand::class, [
        'locales' => [FakeLocale::Incorrect1],
    ])->run();
})->throws(UnknownLocaleCodeException::class);
