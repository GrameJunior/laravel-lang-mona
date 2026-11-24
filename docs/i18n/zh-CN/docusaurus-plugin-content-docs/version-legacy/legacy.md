---
title: Legacy 文档
slug: /
description: 适用于 Mona 发布前的 Laravel-Lang 软件包。
---

如果还在使用独立发布版本的旧 Laravel-Lang 软件包，请使用此文档。要迁移到统一发布的版本，请参阅[升级到 Mona](/upgrade)。

## 快速开始 {#quick-start}

```bash
composer require laravel-lang/common:^6.9
php artisan lang:add fr
```

```php
__('Remember me', locale: 'fr');
// Se souvenir de moi
```

- [快速入门](./basic-usage.mdx)和[可用语言区域](./available-locales-list.mdx)
- [管理语言区域](./manage-locales.mdx)
- [配置](./configuration.mdx)
- [Common 包和兼容性](./packages-common.mdx#compatibility)
- [安装插件](./plugins-installation.mdx)
