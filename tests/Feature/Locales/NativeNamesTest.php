<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\NativeCountryNames\CountryData;
use LaravelLang\NativeCountryNames\CountryNames;
use LaravelLang\NativeCurrencyNames\CurrencyData;
use LaravelLang\NativeCurrencyNames\CurrencyNames;
use LaravelLang\NativeLocaleNames\LocaleData;
use LaravelLang\NativeLocaleNames\LocaleNames;

beforeEach(function () {
    config()->set('localization_main.aliases', ['de' => 'de_DE', 'fr' => 'fr_FR']);
});

dataset('native name services', [
    'locale names'   => [LocaleNames::class, new LocaleData('fr', 'fr_FR', 'Français', 'Französisch')],
    'country names'  => [CountryNames::class, new CountryData('fr', 'FR', 'France', 'Frankreich')],
    'currency names' => [CurrencyNames::class, new CurrencyData('fr', 'EUR', 978, 'euro', 'Euro')],
]);

test('looks up localized names by code, alias and enum', function (string $service, object $french) {
    $names = app($service);

    expect($names->one('de', 'fr'))->toEqual($french)
        ->and($names->one('de_DE', 'fr_FR'))->toEqual($french)
        ->and($names->one(Locale::German, Locale::French))->toEqual($french);
})->with('native name services');

test('sorts localized names for codes, aliases and enums', function (string $service, object $french) {
    $names = app($service);

    foreach (['de', 'de_DE', Locale::German] as $display) {
        $all = $names->all($display);
        expect($all->get('fr'))->toEqual($french);

        $ascending  = $all->map(fn ($item) => $item->localized)->all();
        $descending = $ascending;
        asort($ascending);
        arsort($descending);

        expect($names->all($display, SortDirection::Ascending)->keys()->all())->toBe(array_keys($ascending))
            ->and($names->all($display, SortDirection::Descending)->keys()->all())->toBe(array_keys($descending));
    }
})->with('native name services');
