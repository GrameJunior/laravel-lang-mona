---
title: Translation plugins
description: Install translation packs or publish your own Composer plugin for Mona 20.x.
---

Publisher discovers translation packs installed with Composer. A pack provides a `laravel_lang.json` manifest, PHP or JSON source files, and optionally its own locale dictionaries.

## Install a pack

Install the pack in your Laravel application and update the locales you already use. For example, the Actions pack adds labels for buttons and other actions.

```bash
composer require "laravel-lang/actions:^20.0"
php artisan lang:update
```

Official translation packs use the same 20.x release series and bring in their matching Publisher and Library versions. See the [package catalog](./packages.md) for official packs and [managing translations](./translations.md) to add a locale. For a third-party pack, choose a release that supports Publisher 20.x.

## Create a pack

Start with the [translation template](https://github.com/Laravel-Lang/mona/tree/20.x/src/TranslationsTemplate). In your pack's `composer.json`, require `laravel-lang/publisher:^20.0`. Require `laravel-lang/library:^20.0` if your pack relies on the shared translations. Publisher looks for manifests at `vendor/*/*/laravel_lang.json` in the application.

Place the manifest at the root of your pack. This example publishes a JSON file and a PHP translation group for every installed locale.

```json
{
  "$schema": "https://raw.githubusercontent.com/Laravel-Lang/mona/main/resources/laravel-lang.schema.json",
  "plugins": [
    {
      "files": [
        {"source": "audit.json", "target": "{locale}.json"},
        {"source": "audit.php", "target": "{locale}/audit.php"}
      ]
    }
  ]
}
```

Each `source` path is relative to the pack's `source/` directory. Each `target` is relative to the application's Laravel language directory and contains `{locale}`.

For example, use this in `source/audit.json`:

```json
{
  "Enable audit trail": "Enable audit trail"
}
```

And use this in `source/audit.php`:

```php
<?php

return [
    'enable' => 'Enable audit trail',
];
```

Both look up the English phrase in `locales/fr/default.json` in your pack when publishing French.

```json
{
  "Enable audit trail": "Activer le journal d’audit"
}
```

In the application, `__('Enable audit trail')` reads the JSON translation and `__('audit.enable')` reads the PHP translation. The pack's translations take precedence over shared Library entries for the same phrase. If neither dictionary contains the phrase, Publisher uses the source value.

### Apply translations conditionally

| Manifest property | When a mapping applies |
| --- | --- |
| No condition | Publisher always includes it. |
| `vendor` | The named Composer package is installed. |
| `vendor` and `version` | The installed package satisfies the Composer version constraint. |
| `vendor` and `project: true` | The root project's Composer name matches `vendor`. Publisher checks the name in this mode. |

Use two manifest entries if a mapping should apply both to an installed dependency and to a root project with that name. See the [upgrade guide](./upgrade.md) for converting a legacy provider and [configuration](./configure.md) for skip rules and forced package matching.
