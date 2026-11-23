---
title: Upgrade to Mona 20.x
description: Upgrade a pre-Mona Laravel-Lang application, including Composer dependencies, settings, language files, and PHP integrations.
---

Mona `20.x` is the first shared release line for Laravel-Lang packages that previously had independent major versions. This guide covers the changes to make in your application. Keep a copy of published language files and customized configuration before regenerating either.

## 1. Update Composer requirements

Use PHP `^8.3` and Laravel 12 or 13. In both `require` and `require-dev`, change the constraints for the [packages included in Mona](./packages.md) to `^20.0`. Related packages resolve to the same exact release. Check third-party plugins for Mona compatibility before updating.

Rename direct Composer requirements and update the corresponding PHP imports. Common already includes the three new names packages, so keep direct requirements only when your application uses them as direct dependencies.

| Previous package | Mona package | New PHP namespace |
| --- | --- | --- |
| `laravel-lang/native-country-names` | `laravel-lang/country-names` | `LaravelLang\CountryNames` |
| `laravel-lang/native-currency-names` | `laravel-lang/currency-names` | `LaravelLang\CurrencyNames` |
| `laravel-lang/native-locale-names` | `laravel-lang/locale-names` | `LaravelLang\LocaleNames` |

Migrate [status-generator scripts](./toolkit.md) separately if you use the old `vendor/bin/lang`. The old `laravel-lang/translator` package and its machine translation configuration have no counterpart in Mona.

Once you have adjusted your requirements, preview and then apply the update.

```bash
composer update "laravel-lang/*" --with-all-dependencies --dry-run
composer update "laravel-lang/*" --with-all-dependencies
```

If a third-party plugin also needs to change, include its Composer package name in the update. If Composer scripts boot the application while it still refers to removed classes, run the update with `--no-scripts`, complete the application changes below, and then rerun the scripts. [Composer update options](https://getcomposer.org/doc/03-cli.md#update-u-upgrade)

## 2. Migrate configuration and providers

Update any manually registered Config, Publisher, Routes, and Models providers to their `LangConfigServiceProvider`, `LangPublisherServiceProvider`, `LangRoutesServiceProvider`, and `LangModelsServiceProvider` classes. Remove manual registration of `LaravelLang\Locales\LaravelLangLocalesServiceProvider`. If you use JSON fallback, replace `LaravelLang\JsonFallback\TranslationServiceProvider` with `LaravelLang\JsonFallback\LangJsonFallbackServiceProvider` and update direct references from `Translator` to `OverridedTranslator` in that namespace.

Publish the new [configuration](./configure.md) with `php artisan vendor:publish --tag=localization`, then copy your customizations into the new files. Replace accesses to the old Config facade and `localization` or `localization-private` keys with Laravel configuration or the new typed services. Clear the cached application configuration after editing.

| Previous setting | Mona setting |
| --- | --- |
| `localization.aliases` | `localization_main.aliases`, still a canonical code-to-alias map |
| `localization.inline` | `localization_main.styles`. Set `['default']` for the old non-inline behavior. Mona defaults to `['default', 'inline']`. |
| `localization.align` | Removed. PHP output uses Symfony VarExporter. |
| `localization.smart_punctuation` | `localization_punctuation`, with `enabled` in place of `enable` and `LOCALIZATION_PUNCTUATION_ENABLED` in place of `LOCALIZATION_SMART_ENABLED` |
| `localization.routes.names` and `.group.middlewares` | `localization_routes.names` and `.middlewares` |
| `localization.routes.name_prefix`, `.redirect_default`, `.hide_default` | `localization_routes.prefix`, `.redirect`, `.hide`. Rename the corresponding environment variables to `LOCALIZATION_PREFIX`, `LOCALIZATION_REDIRECT`, and `LOCALIZATION_HIDE`. |
| `localization.models.suffix`, `.helpers`, `.filter.enabled` | `localization_models.suffix`, `.helpers`, `.filter`. `filter` is a boolean. |
| `localization-private.models.directory` | `localization_models.directories`, an array that defaults to `[app_path('Models')]` |
| `localization-private.map` | `localization_map.locales` |
| `localization-private.plugins` and `.packages` | [Plugin manifests](./plugins.md) |

## 3. Move language files to canonical codes

Where previous versions published files under aliases, move or merge them into the canonical paths before republishing. For example, move `lang/de-CH.json` to `lang/de_CH.json` and merge `lang/de-CH/` into `lang/de_CH/`. Check `lang/vendor/<package>/` for the same pattern. Set Laravel's application locale and fallback locale to canonical codes. Keep aliases configured for inputs such as routes.

Use `php artisan lang:add <locale>` for each locale represented only by vendor PHP translations, then regenerate with `php artisan lang:update`. Check the diff for overwritten custom keys, translations from integrations that are no longer published, and numeric keys in HTTP status dictionaries. A generated file can retain old keys even when its integration stops publishing them. Decide whether to keep those entries as application translations.

Remove `lang:reset` and `lang:rm --force` from scripts. Review the [command behavior](./translations.md), especially the update of all installed locales during `lang:add` and the per-locale results of `lang:rm`.

## 4. Update integrations

- **Locales.** The `Locales` facade now returns collections of codes or aliases. Use `Locales::data()` for objects and `installed(false)` for canonical installed codes, including current and fallback. The DTO is now `LaravelLang\LocaleNames\LocaleData`. Its `code` is canonical and `alias` holds the display alias. Handle `UnknownLocaleException` when looking up unchecked input. See the [Locales API](./locales.md).
- **Names.** Resolve the relevant `CountryNames`, `CurrencyNames`, or `LocaleNames` service via Laravel's container. Replace static `get()` and `SortBy` with service methods `all()` or `one()` and optional sorting. Review callers that consumed the previous arrays or mutable objects. See [Locales and names](./locales.md).
- **Routes.** Import `use function LaravelLang\Routes\localizedRoute;`. Update route groups and middleware imports to the [new Routes API](./routes.md), then check generated URL aliases and default-locale redirects.
- **Models.** Convert alias-valued `locale` data in translation tables to canonical codes. Before writing the migration, resolve rows that would collide under the unique `item_id` and `locale` pair. Change references to `TrimCast` to `TrimStringCast`. Check locale argument types, event listeners, and any overrides of `translationModelName()`. See [Models](./models.md).
- **Plugins.** Migrate legacy provider registration to a [`laravel_lang.json` manifest](./plugins.md). Sources under `source/` point to application targets, and their values are the keys in dictionaries at `locales/<code>/<style>.json`. Official packs use `laravel-lang/library` for shared phrases. Use the [Toolkit](./toolkit.md) to migrate old dictionary data.

After migrating, run your application tests and check a translated validation message, a localized URL, and a model translation for each integration you use. The [upgrade map](https://github.com/Laravel-Lang/mona/blob/20.x/UPGRADE-MAP.md) records detailed changes between the pre-Mona and Mona source snapshots.
