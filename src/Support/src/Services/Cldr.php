<?php

declare(strict_types=1);

namespace LaravelLang\Support\Services;

use BackedEnum;
use Illuminate\Support\Collection;
use LaravelLang\Support\Enums\SortBy;
use LaravelLang\Support\Filesystem\Filesystem;

abstract class Cldr
{
    protected static string $fallback = '_native';

    abstract protected static function item(array|string $value, string $locale): mixed;

    abstract protected static function path(string $locale): string;

    public static function all(BackedEnum|string|null $locale = null, SortBy $sortBy = SortBy::None): Collection
    {
        return static::forLocale(static::locale($locale), $sortBy);
    }

    protected static function forLocale(string $locale, SortBy $sortBy): Collection
    {
        return (new Collection(static::load($locale)))
            ->when($sortBy === SortBy::Key, static fn (Collection $items) => $items->sortKeys())
            ->when($sortBy === SortBy::Value, static fn (Collection $items) => $items->sort())
            ->map(static fn (array|string $value, string $code) => static::item($value, $code));
    }

    protected static function load(string $locale): array
    {
        return (new Filesystem())->get(
            static::path($locale)
        );
    }

    protected static function locale(BackedEnum|string|null $locale): string
    {
        if (! $locale) {
            return static::$fallback;
        }

        if ($locale instanceof BackedEnum) {
            $locale = $locale->value;
        }

        if (file_exists(static::path($locale))) {
            return $locale;
        }

        return static::$fallback;
    }
}
