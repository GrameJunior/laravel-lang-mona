---
title: Getting started
description: Install Laravel-Lang and publish your first Laravel translations.
---

## Quick start

```bash
composer require laravel-lang/common
php artisan lang:add fr
```

```php
__('auth.failed', locale: 'fr');
// Ces identifiants ne correspondent pas à nos enregistrements.
```

- [Manage translations](./translations.md) and [browse available locales](./available-locales.md)
- [Configure localization](./configure.md)
- [Check compatibility](./release-notes.md#versioning-and-compatibility) or [upgrade an existing installation](./upgrade.md)
