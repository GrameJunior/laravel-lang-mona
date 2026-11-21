<?php

declare(strict_types=1);

use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('correct', function (): void {
    $locales = Locales::installed();

    expect($locales)->toContain(FakeLocale::Custom);
    expect($locales)->toContain(FakeLocale::Fallback);
    expect($locales)->toContain(FakeLocale::DefaultAlias);

    expect($locales)->not->toContain(FakeLocale::Incorrect1);
    expect($locales)->not->toContain(FakeLocale::Uninstalled);

    expect($locales)->toHaveCount(3);
});
test('incorrect', function (): void {
    ensureLang(FakeLocale::Incorrect1);
    ensureLang('0');

    $locales = Locales::installed();

    expect($locales)->toContain(FakeLocale::Custom);
    expect($locales)->toContain(FakeLocale::Fallback);
    expect($locales)->toContain(FakeLocale::DefaultAlias);

    expect($locales)->not->toContain(FakeLocale::Incorrect1);
    expect($locales)->not->toContain('0');
    expect($locales)->not->toContain(FakeLocale::Uninstalled);

    expect($locales)->toHaveCount(3);
});
