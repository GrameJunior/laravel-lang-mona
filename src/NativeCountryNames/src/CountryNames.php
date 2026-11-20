<?php

declare(strict_types=1);

namespace LaravelLang\NativeCountryNames;

use Illuminate\Support\Collection;
use LaravelLang\Config\Services\AliasLangConfig;
use LaravelLang\Filesystem\Filesystem;
use LaravelLang\LocaleList\Locale;
use SortDirection;

class CountryNames
{
    protected string $default = '_native';

    public function __construct(
        protected AliasLangConfig $config,
        protected Filesystem $filesystem,
    ) {}

    public function all(Locale|string|null $forLocale, ?SortDirection $direction = null): Collection
    {
        return (new Collection($this->read($forLocale)))
            ->map(fn (array $item, string $locale): CountryData => $this->map($locale, $item))
            ->when($direction !== null, fn (Collection $items) => $items->sortBy(
                callback  : 'localized',
                descending: $direction === SortDirection::Descending
            ));
    }

    public function one(Locale|string|null $forLocale, Locale|string $locale): CountryData
    {
        $normalized = $this->normalize($locale);

        $items = $this->read($forLocale);

        return $this->map($normalized, $items[$normalized]);
    }

    protected function map(string $locale, array $item): CountryData
    {
        return new CountryData(
            locale   : $locale,
            code     : $item['code'],
            native   : $item['native'],
            localized: $item['localized'],
        );
    }

    protected function read(Locale|string|null $locale): array
    {
        $normalized = $this->normalize($locale);

        return $this->filesystem->fromJson(__DIR__ . "/../data/$normalized.json");
    }

    protected function normalize(Locale|string|null $locale): string
    {
        if ($locale === $this->default) {
            return $locale;
        }

        return $this->config->from($locale);
    }
}
