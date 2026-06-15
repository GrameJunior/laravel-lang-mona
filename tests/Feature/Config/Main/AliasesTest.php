<?php

declare(strict_types=1);

use LaravelLang\Config\Enums\Name;
use LaravelLang\LocaleList\Locale;

test('aliases', function () {
    setConfig(Name::Main, 'aliases', [
        Locale::German->value => 'de_alias',
        Locale::French->value => 'fr_alias',
    ]);

    expect(getConfig()->main->aliases)
        ->toBe([
            Locale::German->value => 'de_alias',
            Locale::French->value => 'fr_alias',
        ]);
});
