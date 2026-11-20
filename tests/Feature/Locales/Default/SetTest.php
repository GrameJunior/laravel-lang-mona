<?php

declare(strict_types=1);

use Illuminate\Foundation\Events\LocaleUpdated;
use Illuminate\Support\Facades\Event;
use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;

use function Illuminate\Support\enum_value;

test('default', function (BackedEnum|string $locale): void {
    $value = enum_value($locale);

    ensureLang($value);

    Event::fake();

    Locales::set($locale);

    expect(app()->getLocale())->toBe($value);

    Event::assertDispatchedOnce(LocaleUpdated::class);

    Event::assertDispatched(function (LocaleUpdated $event) use ($value): bool {
        if ($event->locale !== $value) {
            return false;
        }

        return $event->previousLocale === FakeLocale::Default;
    });
})->with([
    Locale::Swedish,
    Locale::Swedish->value,
]);
test('uninstalled', function (): void {
    Event::fake();

    Locales::set(Locale::Swedish);

    expect(app()->getLocale())->toBe(FakeLocale::Default);

    Event::assertNothingDispatched();
});
test('incorrect', function (mixed $locale): void {
    Event::fake();

    ensureLang((string) $locale);

    Locales::set($locale);

    expect(app()->getLocale())->toBe(FakeLocale::Default);

    Event::assertNothingDispatched();
})->with('incorrect locales');
