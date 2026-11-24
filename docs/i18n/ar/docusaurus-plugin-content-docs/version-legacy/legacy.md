---
title: "توثيق الإصدارات السابقة"
slug: /
description: "توثيق حزم Laravel-Lang التي صدرت قبل Mona."
---

استخدم هذا التوثيق للتثبيتات التي ما زالت تستخدم حزم Laravel-Lang السابقة ذات الإصدارات المستقلة. لنقل التطبيق إلى سلسلة الإصدارات الموحّدة، راجع [الترقية إلى Mona](/upgrade).

## البدء السريع {#quick-start}

```bash
composer require laravel-lang/common:^6.9
php artisan lang:add fr
```

```php
__('Remember me', locale: 'fr');
// Se souvenir de moi
```

- [البدء](./basic-usage.mdx) و[الإعدادات المحلية المتاحة](./available-locales-list.mdx)
- [إدارة الإعدادات المحلية](./manage-locales.mdx)
- [الإعداد](./configuration.mdx)
- [حزمة Common والتوافق](./packages-common.mdx#compatibility)
- [تثبيت الإضافات](./plugins-installation.mdx)
