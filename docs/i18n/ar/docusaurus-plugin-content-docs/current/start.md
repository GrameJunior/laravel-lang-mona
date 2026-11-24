---
title: "البدء"
description: "ثبّت Laravel-Lang وانشر أولى ترجمات Laravel."
---

## البدء السريع {#quick-start}

```bash
composer require laravel-lang/common
php artisan lang:add fr
```

```php
__('auth.failed', locale: 'fr');
// Ces identifiants ne correspondent pas à nos enregistrements.
```

- [إدارة الترجمات](./translations.md) و[استعراض الإعدادات المحلية المتاحة](./available-locales.md)
- [ضبط إعدادات التوطين](./configure.md)
- [التحقّق من التوافق](./release-notes.md#versioning-and-compatibility) أو [ترقية تثبيت قائم](./upgrade.md)
