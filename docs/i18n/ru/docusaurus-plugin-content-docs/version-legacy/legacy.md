---
title: Документация Legacy
slug: /
description: Документация пакетов Laravel-Lang, выпущенных до Mona.
---

Используйте эту документацию, если в проекте установлены прежние пакеты Laravel-Lang с независимым версионированием. Как перевести приложение на общую ветку версий, см. в разделе [Апгрейд до Mona](/upgrade).

## Быстрый старт {#quick-start}

```bash
composer require laravel-lang/common:^6.9
php artisan lang:add fr
```

```php
__('Remember me', locale: 'fr');
// Se souvenir de moi
```

- [Начало работы](./basic-usage.mdx) и [доступные локали](./available-locales-list.mdx)
- [Управление локалями](./manage-locales.mdx)
- [Конфигурация](./configuration.mdx)
- [Пакет Common и совместимость](./packages-common.mdx#compatibility)
- [Установка плагинов](./plugins-installation.mdx)
