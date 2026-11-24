---
title: "Дакументацыя Legacy"
slug: /
description: "Дакументацыя пакетаў Laravel-Lang, выпушчаных да Mona."
---

Карыстайцеся гэтай дакументацыяй для ўсталяванняў з ранейшымі пакетамі Laravel-Lang, версіі якіх публікаваліся незалежна. Каб перавесці прыкладанне на агульную лінейку рэлізаў, гл. [Апгрэйд да Mona](/upgrade).

## Хуткі пачатак {#quick-start}

```bash
composer require laravel-lang/common:^6.9
php artisan lang:add fr
```

```php
__('Remember me', locale: 'fr');
// Se souvenir de moi
```

- [Пачатак працы](./basic-usage.mdx) і [даступныя лакалі](./available-locales-list.mdx)
- [Кіраванне лакалямі](./manage-locales.mdx)
- [Канфігурацыя](./configuration.mdx)
- [Пакет Common і сумяшчальнасць](./packages-common.mdx#compatibility)
- [Усталяванне плагінаў](./plugins-installation.mdx)
