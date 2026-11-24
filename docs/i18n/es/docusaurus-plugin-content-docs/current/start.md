---
title: Primeros pasos
description: Instala Laravel-Lang y publica tus primeras traducciones de Laravel.
---

## Inicio rápido {#quick-start}

```bash
composer require laravel-lang/common
php artisan lang:add fr
```

```php
__('auth.failed', locale: 'fr');
// Ces identifiants ne correspondent pas à nos enregistrements.
```

- [Gestionar traducciones](./translations.md) y [consultar las configuraciones regionales disponibles](./available-locales.md)
- [Configurar la localización](./configure.md)
- [Comprobar la compatibilidad](./release-notes.md#versioning-and-compatibility) o [actualizar una instalación existente](./upgrade.md)
