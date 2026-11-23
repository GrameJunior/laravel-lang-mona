---
title: Translation plugins
description: Install translation packs or publish your own Composer plugin for Laravel-Lang.
---

Publisher discovers translation packs installed with Composer. A pack provides a `laravel_lang.json` manifest, PHP or JSON source files, and optionally its own locale dictionaries.

## Install a pack

Install the pack in your Laravel application and update the locales you already use. For example, the Actions pack adds labels for buttons and other actions.

```bash
composer require laravel-lang/actions
php artisan lang:update
```

See [managing translations](./translations.md) to add a locale and [community plugins](./plugins-community.md) for third-party packs.

### Official translation packs

Common includes these packs. You can also install each one separately with Composer.

| Package | Translations |
| --- | --- |
| `laravel-lang/lang` | Laravel framework and packages such as Breeze, Cashier, Fortify, Jetstream, Nova, Passkeys, and Laravel UI |
| `laravel-lang/starter-kits` | Laravel's Livewire, React, Svelte, and Vue starter kits |
| `laravel-lang/moonshine` | MoonShine admin panel |
| `laravel-lang/actions` | Buttons and other action labels |
| `laravel-lang/attributes` | Human-readable validation attribute names |
| `laravel-lang/http-statuses` | HTTP status phrases |

Each pack brings in Publisher and the shared Library. Its manifest specifies which integrations and versions it can publish translations for.

## Create a pack

Start with the translation template. In your pack's `composer.json`, require `laravel-lang/publisher` and declare the versions you support. Require `laravel-lang/library` if your pack relies on the shared translations. Publisher looks for manifests at `vendor/*/*/laravel_lang.json` in the application. [Translation template](https://github.com/Laravel-Lang/mona/tree/main/src/TranslationsTemplate)

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
