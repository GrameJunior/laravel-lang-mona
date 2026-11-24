---
title: "Legacy hujjatlari"
slug: /
description: "Mona’dan oldingi Laravel-Lang paketlari uchun hujjatlar."
---

Alohida versiyalangan eski Laravel-Lang paketlaridan foydalanayotgan bo‘lsangiz, shu hujjatlarga murojaat qiling. Ilovani umumiy relizlar qatoriga o‘tkazish uchun [Mona’ga yangilash](/upgrade) bo‘limiga qarang.

## Tez boshlash {#quick-start}

```bash
composer require laravel-lang/common:^6.9
php artisan lang:add fr
```

```php
__('Remember me', locale: 'fr');
// Se souvenir de moi
```

- [Ishni boshlash](./basic-usage.mdx) va [mavjud lokallar](./available-locales-list.mdx)
- [Lokallarni boshqarish](./manage-locales.mdx)
- [Sozlamalar](./configuration.mdx)
- [Common paketi va moslik](./packages-common.mdx#compatibility)
- [Plaginlarni o‘rnatish](./plugins-installation.mdx)
