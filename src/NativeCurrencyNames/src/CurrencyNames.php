<?php

declare(strict_types=1);

namespace LaravelLang\NativeCurrencyNames;

use Illuminate\Support\Collection;
use LaravelLang\Config\Services\AliasLangConfig;
use LaravelLang\Filesystem\Filesystem;
use LaravelLang\LocaleList\Locale;
use SortDirection;

class CurrencyNames
{
    protected string $default = '_native';

    public function __construct(
        protected AliasLangConfig $config,
        protected Filesystem $filesystem,
    ) {}

    public function all(Locale|string|null $forLocale, ?SortDirection $direction = null): Collection
    {
        return (new Collection($this->read($forLocale)))
            ->map(fn (array $item, string $locale) => $this->map($locale, $item))
            ->when($direction !== null, fn (Collection $items) => $items->sortBy(
                'localized', descending: $direction
            ));
    }

    public function one(Locale|string|null $forLocale, Locale|string $locale): CurrencyData
    {
        $normalized = $this->normalize($locale);

        $items = $this->read($forLocale);

        return $this->map($normalized, $items[$normalized]);
    }

    protected function map(string $locale, array $item): CurrencyData
    {
        return new CurrencyData(
            locale   : $locale,
            code     : $item['code'],
            numeric  : $item['numeric'],
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
