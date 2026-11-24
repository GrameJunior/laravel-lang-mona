---
title: Documentación de versiones anteriores
slug: /
description: Documentación de los paquetes de Laravel-Lang publicados antes de Mona.
---

Usa esta documentación si tu instalación aún utiliza los paquetes anteriores de Laravel-Lang, que tenían versiones independientes. Para migrar una aplicación a la serie de versiones compartida, consulta [Actualizar a Mona](/upgrade).

## Inicio rápido {#quick-start}

```bash
composer require laravel-lang/common:^6.9
php artisan lang:add fr
```

```php
__('Remember me', locale: 'fr');
// Se souvenir de moi
```

- [Primeros pasos](./basic-usage.mdx) y [configuraciones regionales disponibles](./available-locales-list.mdx)
- [Gestionar configuraciones regionales](./manage-locales.mdx)
- [Configuración](./configuration.mdx)
- [Paquete Common y compatibilidad](./packages-common.mdx#compatibility)
- [Instalar complementos](./plugins-installation.mdx)
