<?php

declare(strict_types=1);

use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('correct', function () {
    $codes   = Locales::data()->available()->pluck('code')->all();
    $aliases = Locales::data()->available()->pluck('alias')->all();

    expect($codes)->not->toContain(FakeLocale::DefaultAlias);
    expect($codes)->toContain(FakeLocale::Default);

    expect($aliases)->toContain(FakeLocale::DefaultAlias);
    expect($aliases)->not->toContain(FakeLocale::Default);
});
test('incorrect', function () {
    $codes   = Locales::data()->available()->pluck('code')->all();
    $aliases = Locales::data()->available()->pluck('alias')->all();

    expect($codes)->not->toContain(FakeLocale::Incorrect1);
    expect($aliases)->not->toContain(FakeLocale::Incorrect1);
});
