---
title: Початок роботи
description: Установіть Laravel-Lang і опублікуйте перші переклади Laravel.
---

## Швидкий старт {#quick-start}

```bash
composer require laravel-lang/common
php artisan lang:add fr
```

```php
__('auth.failed', locale: 'fr');
// Ces identifiants ne correspondent pas à nos enregistrements.
```

- [Керуйте перекладами](./translations.md) та [перегляньте доступні локалі](./available-locales.md)
- [Налаштуйте локалізацію](./configure.md)
- [Перевірте сумісність](./release-notes.md#versioning-and-compatibility) або [оновіть наявну інсталяцію](./upgrade.md)
