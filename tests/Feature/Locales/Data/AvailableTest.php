<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('correct', function () {
    $locales = Locales::available()->pluck('code')->all();

    expect($locales)->toHaveCount(count(Locale::cases()));

    expect($locales)->toContain(FakeLocale::DefaultAlias);
    expect($locales)->not->toContain(FakeLocale::Default);
});
test('incorrect', function () {
    $locales = Locales::available()->pluck('code')->all();

    expect($locales)->not->toContain(FakeLocale::Incorrect1);
});
