---
title: "Prise en main"
description: "Installez Laravel-Lang et publiez vos premières traductions pour Laravel."
---

## Démarrage rapide {#quick-start}

```bash
composer require laravel-lang/common
php artisan lang:add fr
```

```php
__('auth.failed', locale: 'fr');
// Ces identifiants ne correspondent pas à nos enregistrements.
```

- [Gérer les traductions](./translations.md) et [consulter les langues disponibles](./available-locales.md)
- [Configurer la localisation](./configure.md)
- [Vérifier la compatibilité](./release-notes.md#versioning-and-compatibility) ou [mettre à niveau une installation existante](./upgrade.md)
