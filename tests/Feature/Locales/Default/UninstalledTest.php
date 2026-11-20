<?php

declare(strict_types=1);

use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

test('main', function (): void {
    $locales = Locales::uninstalled();

    expect($locales)->not->toContain(FakeLocale::Custom);
    expect($locales)->not->toContain(app()->getLocale());
    expect($locales)->not->toContain(app()->getFallbackLocale());
});

test('an installed locale is excluded by both its code and its alias', function (): void {
    config()->set('localization_main.aliases.fr', 'fr_FR');

    expect(Locales::installed())->toContain('fr_FR')
        ->and(Locales::uninstalled())->not->toContain('fr_FR')
        ->and(Locales::uninstalled(false))->not->toContain('fr');
});
