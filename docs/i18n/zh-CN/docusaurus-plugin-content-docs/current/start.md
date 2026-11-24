---
title: 快速入门
description: 安装 Laravel-Lang 并为 Laravel 应用发布第一批译文。
---

## 快速开始 {#quick-start}

```bash
composer require laravel-lang/common
php artisan lang:add fr
```

```php
__('auth.failed', locale: 'fr');
// Ces identifiants ne correspondent pas à nos enregistrements.
```

- [管理译文](./translations.md)和[查看可用语言区域](./available-locales.md)
- [配置本地化](./configure.md)
- [检查兼容性](./release-notes.md#versioning-and-compatibility)或[升级现有安装](./upgrade.md)
