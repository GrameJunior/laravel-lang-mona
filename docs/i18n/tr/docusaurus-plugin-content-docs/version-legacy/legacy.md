---
title: "Legacy belgeleri"
slug: /
description: "Mona’dan önce yayımlanan Laravel-Lang paketlerinin belgeleri."
---

Daha önce bağımsız olarak sürümlenen Laravel-Lang paketlerini kullanmaya devam eden kurulumlar için bu belgeleri kullanın. Uygulamayı ortak sürüm serisine geçirmek için [Mona’ya yükseltme](/upgrade) sayfasına bakın.

## Hızlı başlangıç {#quick-start}

```bash
composer require laravel-lang/common:^6.9
php artisan lang:add fr
```

```php
__('Remember me', locale: 'fr');
// Se souvenir de moi
```

- [Başlangıç](./basic-usage.mdx) ve [desteklenen yerel ayarlar](./available-locales-list.mdx)
- [Yerel ayarları yönetme](./manage-locales.mdx)
- [Yapılandırma](./configuration.mdx)
- [Common paketi ve uyumluluk](./packages-common.mdx#compatibility)
- [Eklenti kurma](./plugins-installation.mdx)
