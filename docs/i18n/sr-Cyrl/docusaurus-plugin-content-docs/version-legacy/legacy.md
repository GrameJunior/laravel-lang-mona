---
title: Архивска документација
slug: /
description: Документација за Laravel-Lang пакете објављене пре Mona.
---

Користите ову документацију за инсталације које још користе старије Laravel-Lang пакете са независним верзијама. Да пребаците апликацију на заједничку линију издања, погледајте [Надоградња на Mona](/upgrade).

## Брзи почетак {#quick-start}

```bash
composer require laravel-lang/common:^6.9
php artisan lang:add fr
```

```php
__('Remember me', locale: 'fr');
// Se souvenir de moi
```

- [Почетак рада](./basic-usage.mdx) и [доступни локали](./available-locales-list.mdx)
- [Управљање локалима](./manage-locales.mdx)
- [Подешавање](./configuration.mdx)
- [Пакет Common и компатибилност](./packages-common.mdx#compatibility)
- [Инсталирање додатака](./plugins-installation.mdx)
