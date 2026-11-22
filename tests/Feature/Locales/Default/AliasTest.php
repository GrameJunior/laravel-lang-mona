<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use LaravelLang\NativeLocaleNames\LocaleData;

test('returns no locale code or alias for an empty string', function (): void {
    expect(Locales::fromAlias(''))->toBeNull()
        ->and(Locales::toAlias(''))->toBeNull();
});

test('validates locale input and extracts its code', function (mixed $input, bool $accepted, ?string $code): void {
    expect(Locales::acceptsInput($input))->toBe($accepted)
        ->and(Locales::normalize($input))->toBe($code);
})->with([
    'unspecified locale' => [null, true, null],
    'locale enum'        => [Locale::French, true, 'fr'],
    'locale data'        => [new LocaleData('fr', 'fr_FR', 'Français', 'French'), true, 'fr'],
    'locale string'      => ['de', true, 'de'],
    'invalid type'       => [['fr'], false, null],
]);
