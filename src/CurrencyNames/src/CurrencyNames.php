<?php

declare(strict_types=1);

namespace LaravelLang\CurrencyNames;

use BackedEnum;
use Illuminate\Support\Collection;
use LaravelLang\Support\Enums\SortBy;
use LaravelLang\Support\Filesystem\Filesystem;

use function file_exists;

final class CurrencyNames
{
    protected static string $fallback = '_native';

    /**
     * @param  \BackedEnum|string|null  $locale
     * @param  \LaravelLang\Support\Enums\SortBy  $sortBy
     *
     * @return \Illuminate\Support\Collection<CurrencyData>
     */
    public static function all(BackedEnum|string|null $locale = null, SortBy $sortBy = SortBy::None): Collection
    {
        return self::forLocale(self::locale($locale), $sortBy);
    }

    protected static function forLocale(string $locale, SortBy $sortBy): Collection
    {
        return (new Collection(self::load($locale)))
            ->when($sortBy === SortBy::Key, static fn (Collection $items) => $items->sortKeys())
            ->when($sortBy === SortBy::Value, static fn (Collection $items) => $items->sortBy('localized'))
            ->map(static fn (array $item) => new CurrencyData(...$item));
    }

    protected static function load(string $locale): array
    {
        return (new Filesystem())->get(
            self::path($locale)
        );
    }

    protected static function locale(BackedEnum|string|null $locale): string
    {
        if (! $locale) {
            return self::$fallback;
        }

        if ($locale instanceof BackedEnum) {
            $locale = $locale->value;
        }

        if (file_exists(self::path($locale))) {
            return $locale;
        }

        return self::$fallback;
    }

    protected static function path(string $locale): string
    {
        return __DIR__ . '/../data/' . $locale . '.json';
    }
}
