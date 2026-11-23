---
title: Getting started
description: Install Laravel-Lang Mona 20.x and publish your first Laravel translations.
---

Mona is the Laravel-Lang monorepo. All first-party packages in Mona are released together as `20.x`. Their Composer manifests use `self.version` for dependencies on one another, so Composer installs connected packages at the same release version. Use `^20.0` for the Mona packages you require directly.

## Requirements

- PHP 8.3 or newer in the PHP 8 series (`^8.3`).
- Laravel 12 or 13. Your Laravel version may require a newer PHP version.
- PHP extensions required by your packages. The Common bundle brings in packages that require `intl` and `json`.

## Install and publish translations

Run these commands from your Laravel application directory. If Laravel-Lang is already installed, follow the [upgrade guide](./upgrade.md) first.

```bash
composer require "laravel-lang/common:^20.0"
php artisan lang:add fr
```

`laravel-lang/common` bundles first-party translations and localization tools. For framework translations alone, install `laravel-lang/lang:^20.0` in place of Common. Both bring in the Publisher and the shared translation library. See the [package reference](./packages.md) to choose individual packages.

The root `laravel-lang/mona` package is used to develop the monorepo. Applications install the published packages listed in the reference.

`lang:add fr` publishes French translations under Laravel's language path, normally `lang/`. For example, the command generates `lang/fr/auth.php`, which Laravel loads through its usual translation helper.

```php
__('auth.failed', locale: 'fr');
```

If your application uses Laravel's default locale settings, set French as the application locale in `.env` and clear any cached configuration.

```dotenv
APP_LOCALE=fr
APP_FALLBACK_LOCALE=en
```

```bash
php artisan config:clear
```

## Next steps

- [Manage translations](./translations.md) with Artisan.
- [Browse available locales](./available-locales.md) or [resolve codes and aliases in PHP](./locales.md).
- [Configure localization](./configure.md) or add [localized routes](./routes.md) and [model translations](./models.md).
