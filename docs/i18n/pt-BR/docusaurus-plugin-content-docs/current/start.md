---
title: Primeiros passos
description: Instale o Laravel-Lang e publique suas primeiras traduções do Laravel.
---

## Início rápido {#quick-start}

```bash
composer require laravel-lang/common
php artisan lang:add fr
```

```php
__('auth.failed', locale: 'fr');
// Ces identifiants ne correspondent pas à nos enregistrements.
```

- [Gerencie as traduções](./translations.md) e [consulte os idiomas disponíveis](./available-locales.md)
- [Configure a localização](./configure.md)
- [Verifique a compatibilidade](./release-notes.md#versioning-and-compatibility) ou [atualize uma instalação existente](./upgrade.md)
