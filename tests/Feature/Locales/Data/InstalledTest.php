<?php

declare(strict_types=1);

use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('correct codes', function () {
    $locales = Locales::data()->installed()->pluck('code')->all();

    expect($locales)->toContain(FakeLocale::Custom);
    expect($locales)->toContain(FakeLocale::Fallback);
    expect($locales)->not->toContain(FakeLocale::DefaultAlias);

    expect($locales)->not->toContain(FakeLocale::Incorrect1);
    expect($locales)->not->toContain(FakeLocale::Uninstalled);

    expect($locales)->toHaveCount(3);
});

test('correct aliases', function () {
    $locales = Locales::data()->installed()->pluck('alias')->all();

    expect($locales)->toContain(FakeLocale::Custom);
    expect($locales)->toContain(FakeLocale::Fallback);
    expect($locales)->toContain(FakeLocale::DefaultAlias);

    expect($locales)->not->toContain(FakeLocale::Incorrect1);
    expect($locales)->not->toContain(FakeLocale::Uninstalled);

    expect($locales)->toHaveCount(3);
});

test('incorrect', function () {
    ensureLang(FakeLocale::Incorrect1);

    $locales = Locales::data()->installed()->pluck('alias')->all();

    expect($locales)->toContain(FakeLocale::Custom);
    expect($locales)->toContain(FakeLocale::Fallback);
    expect($locales)->toContain(FakeLocale::DefaultAlias);

    expect($locales)->not->toContain(FakeLocale::Incorrect1);
    expect($locales)->not->toContain(FakeLocale::Uninstalled);

    expect($locales)->toHaveCount(3);
});
