<?php

declare(strict_types=1);

namespace LaravelLang\NativeLocaleNames;

use Illuminate\Support\Collection;
use LaravelLang\Config\Services\AliasLangConfig;
use LaravelLang\Filesystem\Filesystem;
use LaravelLang\LocaleList\Locale;
use SortDirection;

class LocaleNames
{
    protected string $default = '_native';

    public function __construct(
        protected AliasLangConfig $config,
        protected Filesystem $filesystem,
    ) {}

    /** @return Collection<string, LocaleData> */
    public function all(Locale|string|null $forLocale, ?SortDirection $direction = null): Collection
    {
        return (new Collection($this->read($forLocale)))
            ->map(fn (array $item, string $locale): LocaleData => $this->map($locale, $item))
            ->when($direction !== null, fn (Collection $items) => $items->sortBy(
                callback  : 'localized',
                descending: $direction === SortDirection::Descending
            ));
    }

    public function one(Locale|string|null $forLocale, Locale|string $locale): LocaleData
    {
        $normalized = $this->normalize($locale);

        $items = $this->read($forLocale);

        return $this->map($normalized, $items[$normalized]);
    }

    /** @param array{native: string, localized: string} $item */
    protected function map(string $locale, array $item): LocaleData
    {
        return new LocaleData(
            code     : $locale,
            alias    : $this->config->to($locale) ?? $locale,
            native   : $item['native'],
            localized: $item['localized'],
        );
    }

    /** @return array<string, array{native: string, localized: string}> */
    protected function read(Locale|string|null $locale): array
    {
        $normalized = $this->normalize($locale);

        return $this->filesystem->fromJson(__DIR__ . "/../data/$normalized.json");
    }

    protected function normalize(Locale|string|null $locale): string
    {
        return $locale === $this->default
            ? $locale
            : $this->config->from($locale) ?? $this->default;
    }
}
