<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\Publisher\Console\Add;

use function Pest\Laravel\artisan;

test('add locale', function (bool $inline, string $foo, string $bar, string $baz): void {
    config()->set('localization.inline', $inline);

    $locale = Locale::Swedish->value;

    expect(__('auth.foo'))->toBe('auth.foo');
    expect(__('bar.bar'))->toBe('bar.bar');
    expect(__('baz'))->toBe('baz');

    expect(__('Foo'))->toBe('Toutes les données ont été chargées.');

    artisan(Add::class, [
        'locales' => [$locale],
    ])->assertSuccessful()->run();

    app()->setlocale($locale);

    expect(__('auth.foo'))->toBe($foo);
    expect(__('bar.bar'))->toBe($bar);
    expect(__('baz'))->toBe($baz);

    expect(__('Foo'))->toBe('Alle Ressourcen geladen.');
})->with([
    'attributes' => [
        'inline' => false,
        'foo'    => 'Foo :attribute 1',
        'bar'    => 'Bar :attribute 1',
        'baz'    => 'Baz :attribute 1',
    ],

    'inline' => [
        'inline' => true,
        'foo'    => 'Foo field 1',
        'bar'    => 'Bar field 1',
        'baz'    => 'Baz field 1',
    ],
]);
