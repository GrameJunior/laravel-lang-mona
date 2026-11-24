---
title: "Legacy-Dokumentation"
slug: /
description: "Dokumentation für Laravel-Lang-Pakete, die vor Mona veröffentlicht wurden."
---

Verwende diese Dokumentation für Installationen, die noch die früheren, separat versionierten Laravel-Lang-Pakete nutzen. Informationen zum Umstieg auf die gemeinsame Versionsreihe findest du unter [Upgrade auf Mona](/upgrade).

## Schnellstart {#quick-start}

```bash
composer require laravel-lang/common:^6.9
php artisan lang:add fr
```

```php
__('Remember me', locale: 'fr');
// Se souvenir de moi
```

- [Erste Schritte](./basic-usage.mdx) und [verfügbare Locales](./available-locales-list.mdx)
- [Locales verwalten](./manage-locales.mdx)
- [Konfiguration](./configuration.mdx)
- [Paket Common und Kompatibilität](./packages-common.mdx#compatibility)
- [Plugins installieren](./plugins-installation.mdx)
