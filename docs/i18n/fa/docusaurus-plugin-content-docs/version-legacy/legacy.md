---
title: مستندات نسخه‌های قدیمی
slug: /
description: مستندات بسته‌های Laravel-Lang منتشرشده پیش از Mona.
---

از این مستندات برای نصب‌هایی استفاده کنید که هنوز بسته‌های قدیمی Laravel-Lang با نسخه‌بندی مستقل را دارند. برای انتقال برنامه به شاخهٔ انتشار مشترک، [ارتقا به Mona](/upgrade) را ببینید.

## شروع سریع {#quick-start}

```bash
composer require laravel-lang/common:^6.9
php artisan lang:add fr
```

```php
__('Remember me', locale: 'fr');
// Se souvenir de moi
```

- [شروع کار](./basic-usage.mdx) و [زبان‌های منطقه‌ای موجود](./available-locales-list.mdx)
- [مدیریت زبان‌های منطقه‌ای](./manage-locales.mdx)
- [پیکربندی](./configuration.mdx)
- [بستهٔ Common و سازگاری](./packages-common.mdx#compatibility)
- [نصب افزونه‌ها](./plugins-installation.mdx)
