<?php

declare(strict_types=1);

use Illuminate\Foundation\Events\LocaleUpdated;
use Illuminate\Support\Facades\Event;
use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;

use function Illuminate\Support\enum_value;

test('default', function (BackedEnum|string $locale) {
    $value = enum_value($locale);

    ensureLang($value);

    Event::fake();

    Locales::set($locale);

    expect(app()->getLocale())->toBe($value);

    Event::assertDispatchedOnce(LocaleUpdated::class);

    Event::assertDispatched(LocaleUpdated::class, function (LocaleUpdated $event) use ($value) {
        if ($event->locale !== $value) {
            return false;
        }

        return $event->previousLocale === 'fr';
    });
})->with([
    Locale::Swedish,
    Locale::Swedish->value,
]);
test('uninstalled', function () {
    Event::fake();

    Locales::set(Locale::Swedish);

    expect(app()->getLocale())->toBe('fr');

    Event::assertNothingDispatched();
});
test('incorrect', function (mixed $locale) {
    Event::fake();

    ensureLang((string) $locale);

    Locales::set($locale);

    expect(app()->getLocale())->toBe('fr');

    Event::assertNothingDispatched();
})->with('incorrect locales');
