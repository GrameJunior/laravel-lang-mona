---
title: Package catalog
description: Choose Laravel-Lang 20.x translation packs, runtime packages, and maintainer tools.
---

Use `laravel-lang/common` to install the official translation packs and the runtime packages for routes, models, and locale data together. For a smaller installation, require the packages that provide the features you need.

```bash
composer require "laravel-lang/common:^20.0"
```

All Mona packages share the same release version. Use `^20.0` for each direct Laravel-Lang dependency you want to keep on 20.x. Inter-package dependencies use Composer's `self.version` so Composer selects matching releases where the packages depend on each other.

## Translation packs

Install `laravel-lang/lang` for Laravel's framework translations. Each official pack pulls in Publisher and the shared Library.

```bash
composer require "laravel-lang/lang:^20.0"
```

[Publish the languages you need](./translations.md) after Composer installs the pack.

| Package | Publishes translations for |
| --- | --- |
| `laravel-lang/lang` | Laravel 12 and 13, Breeze 2, Cashier Stripe 15 and 16, Fortify 1, Jetstream 5, Nova 4 and 5, Passkeys 0.x, and Laravel UI 4 |
| `laravel-lang/starter-kits` | Laravel's Livewire, React, Svelte, and Vue starter kits. The root Composer project name selects the kit |
| `laravel-lang/moonshine` | MoonShine 3, 4, and 5 |
| `laravel-lang/actions` | Common action labels, such as button text, in JSON and `actions.php` |
| `laravel-lang/attributes` | Human-readable validation attribute names in `validation.php` |
| `laravel-lang/http-statuses` | HTTP status phrases in JSON and numeric entries in `http-statuses.php` |

The package's `laravel_lang.json` defines the supported dependency versions and which files Publisher writes. See [translation plugins](./plugins.md) for how these manifests are selected.

## Application packages

| Package | Use it to |
| --- | --- |
| `laravel-lang/publisher` | Publish, update, or remove translation files with [Artisan](./translations.md) |
| `laravel-lang/routes` | Generate [localized URLs](./routes.md) and select the locale for a request |
| `laravel-lang/models` | Keep [Eloquent attribute translations](./models.md) in a separate table |
| `laravel-lang/locale-list` | List supported locale codes with the `Locale` enum |
| `laravel-lang/locales` | [Inspect installed locales and set the application language](./locales.md) |
| `laravel-lang/locale-names` | Get [native and localized names](./locales.md#look-up-locale-country-and-currency-names) for locales |
| `laravel-lang/country-names` | Get [country names and ISO codes](./locales.md#look-up-locale-country-and-currency-names) by locale |
| `laravel-lang/currency-names` | Get [currency names and ISO codes](./locales.md#look-up-locale-country-and-currency-names) by locale |
| `laravel-lang/json-fallback` | Enable [fallback for JSON translations](./translations.md#json-fallback) |

## Shared dependencies and maintainer tools

| Package | Role |
| --- | --- |
| `laravel-lang/config` | [Shared configuration](./configure.md) for publishing, locale aliases, routes, and models |
| `laravel-lang/library` | Shared dictionaries of translated phrases used by Publisher |
| `laravel-lang/filesystem` | Shared file discovery and serialization |
| `laravel-lang/exceptions` | Shared exception types |
| `laravel-lang/toolkit` | [Maintainer commands](./toolkit.md) for translation repositories |
| `laravel-lang/translations-template` | An example translation pack with a manifest and source files |

The `laravel-lang/mona` repository contains the source for the split packages. Install the application packages listed above from your Laravel app. Start with the [installation guide](./start.md) or use the [upgrade guide](./upgrade.md) for an existing Laravel-Lang installation.
