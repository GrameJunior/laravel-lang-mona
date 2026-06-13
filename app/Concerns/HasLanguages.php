<?php

declare(strict_types=1);

namespace LaravelLang\Mona\Concerns;

trait HasLanguages
{
    protected string $sourceDirectory = __DIR__ . '/../../node_modules/cldr-localenames-full/main';

    /** @var array<string, array<string, string>> */
    protected array $languages = [];

    protected function languages(string $locale): array
    {
        $source = $this->bundleCode($locale);

        return $this->languages[$source] ??= $this->load(
            $this->sourceDirectory . '/' . $source . '/languages.json'
        );
    }
}
