<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use LaravelLang\Publisher\Console\UpdateCommand;

use function Pest\Laravel\artisan;

test('update styles', function (array $config, array $foo, array $bar, array $baz): void {
    config()->set('localization_main.styles', $config);

    $locale = Locale::Swedish->value;

    expect(__('auth.foo'))->toBe('auth.foo');
    expect(__('bar.bar'))->toBe('bar.bar');
    expect(__('baz'))->toBe('baz');

    expect(__('Foo'))->toBe('Toutes les données ont été chargées.');

    artisan(UpdateCommand::class)->assertSuccessful()->run();

    Locales::reload();

    expect(__('auth.foo'))->toBe($foo[0]);
    expect(__('bar.bar'))->toBe($bar [0]);
    expect(__('baz'))->toBe($baz[0]);

    expect(__('Foo'))->toBe('Toutes les données ont été chargées.');

    ensureLang($locale);

    Locales::reload();

    artisan(UpdateCommand::class)->assertSuccessful()->run();

    app()->setlocale($locale);

    expect(__('auth.foo'))->toBe($foo[1]);
    expect(__('bar.bar'))->toBe($bar[1]);
    expect(__('baz'))->toBe($baz[1]);

    expect(__('Foo'))->toBe('Alle Ressourcen geladen.');
})->with([
    'default' => [
        'config' => ['default'],
        'foo'    => ['Foo :attribute 0', 'Foo :attribute 1'],
        'bar'    => ['Bar :attribute 0', 'Bar :attribute 1'],
        'baz'    => ['Baz :attribute 0', 'Baz :attribute 1'],
    ],

    'inline' => [
        'config' => ['inline'],
        'foo'    => ['Foo :attribute 0', 'Foo field 1'],
        'bar'    => ['Bar :attribute 0', 'Bar field 1'],
        'baz'    => ['Baz :attribute 0', 'Baz field 1'],
    ],

    'empty' => [
        'config' => [],
        'foo'    => ['Foo :attribute 0', 'Foo :attribute 1'],
        'bar'    => ['Bar :attribute 0', 'Bar :attribute 1'],
        'baz'    => ['Baz :attribute 0', 'Baz :attribute 1'],
    ],
]);
