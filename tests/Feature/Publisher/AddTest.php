<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\Publisher\Console\Add;

use function Pest\Laravel\artisan;

test('add locale', function (bool $inline) {
    config()->set('localization.inline', $inline);

    $locale = Locale::Swedish->value;

    // Установить директорию фейкового плагина

    expect(lang_path("$locale/bar.php"))->not->toBeReadableFile();

    expect(__('auth.foo'))->toBe('auth.foo');
    expect(__('bar.bar'))->toBe('bar.bar');
    expect(__('baz'))->toBe('baz');

    expect(__('Foo'))->toBe('Toutes les données ont été chargées.');

    artisan(Add::class, [
        'locales' => [$locale],
    ])->assertSuccessful()->run();

    expect(lang_path("$locale/auth.php"))->toBeReadableFile();
    expect(lang_path("$locale/bar.php"))->toBeReadableFile();
    expect(lang_path("$locale.json"))->toBeReadableFile();

    app()->setlocale($locale);

    expect(__('auth.foo'))->toBe($inline ? 'Foo field 1' : 'Foo :attribute 1');
    expect(__('bar.bar'))->toBe($inline ? 'Bar field 1' : 'Bar :attribute 1');
    expect(__('baz'))->toBe($inline ? 'Baz field 1' : 'Baz :attribute 1');

    expect(__('Foo'))->toBe('Alle Ressourcen geladen.');
})->with('booleans');
