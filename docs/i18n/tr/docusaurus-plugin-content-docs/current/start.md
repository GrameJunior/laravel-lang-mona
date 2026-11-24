---
title: "Başlangıç"
description: "Laravel-Lang’i kurun ve Laravel için ilk çevirilerinizi yayımlayın."
---

## Hızlı başlangıç {#quick-start}

```bash
composer require laravel-lang/common
php artisan lang:add fr
```

```php
__('auth.failed', locale: 'fr');
// Ces identifiants ne correspondent pas à nos enregistrements.
```

- [Çevirileri yönetin](./translations.md) ve [desteklenen yerel ayarlara göz atın](./available-locales.md)
- [Yerelleştirmeyi yapılandırın](./configure.md)
- [Uyumluluğu kontrol edin](./release-notes.md#versioning-and-compatibility) veya [mevcut bir kurulumu yükseltin](./upgrade.md)
