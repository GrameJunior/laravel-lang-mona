<?php

declare(strict_types=1);

namespace LaravelLang\Mona\Commands\Cldr;

use RuntimeException;

use function explode;
use function sprintf;
use function str_replace;

final class CldrLocaleCommand extends CldrCommand
{
    protected string $directory = __DIR__ . '/../../../src/LocaleNames/data';

    private const string SOURCE_DIRECTORY = __DIR__ . '/../../../node_modules/cldr-localenames-full/main';
    private const string FALLBACK_LOCALE  = 'en';
    private const array  LOCALE_ALIASES   = [
        'pt_BR' => 'pt',
        'tl'    => 'fil',
        'zh_CN' => 'zh-Hans',
        'zh_HK' => 'zh-Hant-HK',
        'zh_TW' => 'zh-Hant',
    ];

    /** @var array<string, array<string, string>> */
    private array $languages = [];

    protected function perform(string $forCode, string $locale): string
    {
        $forLanguages = $this->languages($forCode);
        $fallback     = $this->languages(self::FALLBACK_LOCALE);

        $code = $this->localeCode($locale);
        $base = $this->baseCode($code);

        return $forLanguages[$code]
            ?? $forLanguages[$base]
            ?? $fallback[$code]
            ?? $fallback[$base]
            ?? throw new RuntimeException(
                sprintf('Unable to find CLDR locale name for [%s] in [%s].', $locale, $forCode)
            );
    }

    protected function languages(string $locale): array
    {
        $source = $this->bundleCode($locale);

        return $this->languages[$source] ??= $this->load(
            self::SOURCE_DIRECTORY . '/' . $source . '/languages.json'
        );
    }

    protected function bundleCode(string $locale): string
    {
        return self::LOCALE_ALIASES[$locale] ?? $this->localeCode($locale);
    }

    protected function localeCode(string $locale): string
    {
        return str_replace('_', '-', $locale);
    }

    protected function baseCode(string $locale): string
    {
        return explode('-', $locale)[0];
    }
}
