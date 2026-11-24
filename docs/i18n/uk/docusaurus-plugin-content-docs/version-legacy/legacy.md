---
title: Документація попередніх версій
slug: /
description: Документація пакетів Laravel-Lang, випущених до Mona.
---

Використовуйте цю документацію для інсталяцій, які досі використовують старі пакети Laravel-Lang із незалежними версіями. Щоб перевести застосунок на спільну серію випусків, див. [Оновлення до Mona](/upgrade).

## Швидкий старт {#quick-start}

```bash
composer require laravel-lang/common:^6.9
php artisan lang:add fr
```

```php
__('Remember me', locale: 'fr');
// Se souvenir de moi
```

- [Початок роботи](./basic-usage.mdx) та [доступні локалі](./available-locales-list.mdx)
- [Керування локалями](./manage-locales.mdx)
- [Налаштування](./configuration.mdx)
- [Пакет Common і сумісність](./packages-common.mdx#compatibility)
- [Установлення плагінів](./plugins-installation.mdx)
