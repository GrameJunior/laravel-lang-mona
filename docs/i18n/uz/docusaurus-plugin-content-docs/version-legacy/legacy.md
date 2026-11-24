---
title: "Oldingi versiyalar hujjatlari"
slug: /
description: "Laravel-Lang paketlarining Mona’dan oldingi chiqarilishlari uchun hujjatlar."
---

Hali Laravel-Lang paketlarining alohida versiyalangan oldingi chiqarilishlaridan foydalanuvchi o‘rnatmalar uchun shu hujjatlardan foydalaning. Ilovani umumiy chiqarilish qatoriga o‘tkazish uchun [Mona’ga yangilash](/upgrade) bo‘limiga qarang.

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
