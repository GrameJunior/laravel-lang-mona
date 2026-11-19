<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use LaravelLang\Publisher\Console\UpdateCommand;

use function Pest\Laravel\artisan;

test('update styles', function (array $config, array $foo, array $bar, array $baz): void {
    config()->set('localization_main.styles', $config);

    $locale = Locale::Swedish->value;

    expect([
        __('auth.foo'),
        __('bar.bar'),
        __('baz'),
        __('Foo'),
    ])->toMatchSnapshot();

    artisan(UpdateCommand::class)->assertSuccessful()->run();

    Locales::reload();

    expect(__('auth.foo'))->toBe($foo[0]);
    expect(__('bar.bar'))->toBe($bar [0]);
    expect(__('baz'))->toBe($baz[0]);

    expect(__('Foo'))->toMatchSnapshot();

    ensureLang($locale);

    Locales::reload();

    artisan(UpdateCommand::class)->assertSuccessful()->run();

    app()->setlocale($locale);

    expect(__('auth.foo'))->toBe($foo[1]);
    expect(__('bar.bar'))->toBe($bar[1]);
    expect(__('baz'))->toBe($baz[1]);

    expect(__('Foo'))->toMatchSnapshot();
})->with('translations');
