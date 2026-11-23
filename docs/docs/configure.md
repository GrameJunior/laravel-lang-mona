---
title: Configuration
description: Configure Mona's translation styles, locale aliases, publication filters, punctuation, routes, and models.
---

Mona loads default settings automatically. Publish the files you need to customize into your application's `config/` directory.

```bash
php artisan vendor:publish --tag=localization_main
```

The `localization` tag publishes all five files. Each filename without `.php` is also a publication tag and a Laravel configuration key.

| File | Settings |
| --- | --- |
| `localization_main.php` | Translation styles, aliases, and publication filters |
| `localization_punctuation.php` | Smart punctuation and locale-specific quote marks |
| `localization_routes.php` | [Route names, locale sources, and middleware](./routes.md) |
| `localization_models.php` | [Translation model suffix, filtering, and generator paths](./models.md) |
| `localization_map.php` | Locale metadata such as script, region, and direction |

After changing configuration, clear the config cache. Run `php artisan lang:update` to regenerate translations affected by publication settings.

## Translation styles

`localization_main.styles` defaults to `['default', 'inline']`. The Publisher loads the corresponding dictionaries in list order. A later style can override an earlier translation for the same phrase. If the list omits `default`, the Publisher prepends it.

The inline style provides generic validation messages such as “This field must be accepted.” To use the standard style, set `styles` to `['default']`.

## Locale aliases

Set `localization_main.aliases` as a map from canonical codes to aliases. For example, add the following entry to the returned array in `config/localization_main.php`.

```php
'aliases' => [
    'de_CH' => 'de-CH',
],
```

Artisan can accept `de-CH`, while Laravel and published files use `de_CH`. Set `APP_LOCALE` and `APP_FALLBACK_LOCALE` to canonical codes. See [Locales and names](./locales.md) for conversion and lookup examples.

## Skip files and force integrations

- `localization_main.skip` matches case-insensitive `*` patterns against full paths with `/` separators. It can exclude source files, dictionaries, and publication targets. For example, `['*/lang/fr/validation.php']` keeps the Publisher from writing that target in an application using `lang/`.
- `localization_main.force` accepts Composer package names. It lets the Publisher use manifest entries even if the named dependency is absent, the installed version does not match, or the root project name differs. Use it when the manifest's translations are applicable to your application.

`skip` takes precedence over `force`.

## Smart punctuation

Set `LOCALIZATION_PUNCTUATION_ENABLED=true` to enable typographic quotes, dashes, and ellipses in published translations. Edit `localization_punctuation.common` for general rules and `localization_punctuation.locales` for overrides keyed by canonical locale code. Published strings take on these rules the next time the Publisher writes them.
