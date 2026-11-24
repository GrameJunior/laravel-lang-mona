---
title: "Documentation Legacy"
slug: /
description: "Documentation des paquets Laravel-Lang publiés avant Mona."
---

Cette documentation s’adresse aux installations qui utilisent encore les anciens paquets Laravel-Lang, dont les versions étaient gérées séparément. Pour faire passer une application aux versions communes des paquets, consultez [Mettre à niveau vers Mona](/upgrade).

## Démarrage rapide {#quick-start}

```bash
composer require laravel-lang/common:^6.9
php artisan lang:add fr
```

```php
__('Remember me', locale: 'fr');
// Se souvenir de moi
```

- [Prise en main](./basic-usage.mdx) et [langues disponibles](./available-locales-list.mdx)
- [Gestion des langues](./manage-locales.mdx)
- [Configuration](./configuration.mdx)
- [Paquet Common et compatibilité](./packages-common.mdx#compatibility)
- [Installation des plugins](./plugins-installation.mdx)
