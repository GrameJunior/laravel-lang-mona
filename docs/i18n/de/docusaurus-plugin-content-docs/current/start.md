---
title: "Erste Schritte"
description: "Installiere Laravel-Lang und veröffentliche deine ersten Laravel-Übersetzungen."
---

## Schnellstart {#quick-start}

```bash
composer require laravel-lang/common
php artisan lang:add fr
```

```php
__('auth.failed', locale: 'fr');
// Ces identifiants ne correspondent pas à nos enregistrements.
```

- [Übersetzungen verwalten](./translations.md) und [verfügbare Locales ansehen](./available-locales.md)
- [Lokalisierung konfigurieren](./configure.md)
- [Kompatibilität prüfen](./release-notes.md#versioning-and-compatibility) oder [eine bestehende Installation aktualisieren](./upgrade.md)
