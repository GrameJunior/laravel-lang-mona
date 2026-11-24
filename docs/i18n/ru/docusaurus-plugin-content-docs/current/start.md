---
title: Начало работы
description: Установите Laravel-Lang и опубликуйте первые переводы для Laravel.
---

## Быстрый старт {#quick-start}

```bash
composer require laravel-lang/common
php artisan lang:add fr
```

```php
__('auth.failed', locale: 'fr');
// Ces identifiants ne correspondent pas à nos enregistrements.
```

- [Управляйте переводами](./translations.md) и [смотрите список доступных локалей](./available-locales.md)
- [Настройте локализацию](./configure.md)
- [Проверьте совместимость](./release-notes.md#versioning-and-compatibility) или [обновите существующую установку](./upgrade.md)
