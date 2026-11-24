---
title: Почетак рада
description: Инсталирајте Laravel-Lang и објавите прве преводе за Laravel.
---

## Брзи почетак {#quick-start}

```bash
composer require laravel-lang/common
php artisan lang:add fr
```

```php
__('auth.failed', locale: 'fr');
// Ces identifiants ne correspondent pas à nos enregistrements.
```

- [Управљајте преводима](./translations.md) и [прегледајте доступне локале](./available-locales.md)
- [Подесите локализацију](./configure.md)
- [Проверите компатибилност](./release-notes.md#versioning-and-compatibility) или [надоградите постојећу инсталацију](./upgrade.md)
