---
title: شروع کار
description: Laravel-Lang را نصب کنید و نخستین ترجمه‌های Laravel را منتشر کنید.
---

## شروع سریع {#quick-start}

```bash
composer require laravel-lang/common
php artisan lang:add fr
```

```php
__('auth.failed', locale: 'fr');
// Ces identifiants ne correspondent pas à nos enregistrements.
```

- [مدیریت ترجمه‌ها](./translations.md) و [مشاهدهٔ زبان‌های منطقه‌ای موجود](./available-locales.md)
- [پیکربندی بومی‌سازی](./configure.md)
- [بررسی سازگاری](./release-notes.md#versioning-and-compatibility) یا [ارتقای نصب فعلی](./upgrade.md)
