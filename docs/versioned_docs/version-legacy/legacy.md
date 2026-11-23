---
title: Legacy documentation
slug: /
description: Documentation for Laravel-Lang packages released before Mona 20.x.
---

Use this documentation for installations that still use the earlier, independently versioned Laravel-Lang packages. To move an application to the shared release line, see [Upgrade to Mona 20.x](/20.x/upgrade).

## Quick start

```bash
composer require laravel-lang/common:^6.9
php artisan lang:add fr
```

```php
__('Remember me', locale: 'fr');
// Se souvenir de moi
```

- [Getting started](./basic-usage.mdx) and [available locales](./available-locales-list.mdx)
- [Managing locales](./manage-locales.mdx)
- [Configuration](./configuration.mdx)
- [Common package and compatibility](./packages-common.mdx#compatibility)
- [Installing plugins](./plugins-installation.mdx)
