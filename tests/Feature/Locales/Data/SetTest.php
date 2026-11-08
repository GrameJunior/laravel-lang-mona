<?php

declare(strict_types=1);

namespace Tests\Feature\Locales\Data;

use BackedEnum;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Events\LocaleUpdated;
use Illuminate\Support\Facades\Event;
use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

use function Illuminate\Support\enum_value;

class SetTest extends TestCase
{
    #[TestWith([Locale::Swedish])]
    #[TestWith([Locale::Swedish->value])]
    public function testDefault(BackedEnum|string $locale): void
    {
        $value = enum_value($locale);

        (new Filesystem())->ensureDirectoryExists(lang_path($value));

        Event::fake();

        Locales::set($locale);

        $this->assertSame($value, app()->getLocale());

        Event::assertDispatchedOnce(LocaleUpdated::class);

        Event::assertDispatched(LocaleUpdated::class, function (LocaleUpdated $event) use ($value) {
            if ($event->locale !== $value) {
                return false;
            }

            return $event->previousLocale === 'fr';
        });
    }

    public function testUninstalled(): void
    {
        Event::fake();

        Locales::set(Locale::Swedish);

        $this->assertSame('fr', app()->getLocale());

        Event::assertNothingDispatched();
    }

    #[DataProviderExternal(TestCase::class, 'dateIncorrectLocales')]
    public function testIncorrect(mixed $locale): void
    {
        Event::fake();

        (new Filesystem())->ensureDirectoryExists(lang_path((string) $locale));

        Locales::set($locale);

        $this->assertSame('fr', app()->getLocale());

        Event::assertNothingDispatched();
    }
}
