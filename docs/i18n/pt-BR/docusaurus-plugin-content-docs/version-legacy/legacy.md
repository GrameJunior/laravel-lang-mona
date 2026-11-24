---
title: Documentação Legacy
slug: /
description: Documentação dos pacotes Laravel-Lang lançados antes do Mona.
---

Use esta documentação para instalações que ainda usam os pacotes Laravel-Lang anteriores, versionados de forma independente. Para migrar uma aplicação para a linha de versões compartilhada, consulte [Atualizar para o Mona](/upgrade).

## Início rápido {#quick-start}

```bash
composer require laravel-lang/common:^6.9
php artisan lang:add fr
```

```php
__('Remember me', locale: 'fr');
// Se souvenir de moi
```

- [Primeiros passos](./basic-usage.mdx) e [localidades disponíveis](./available-locales-list.mdx)
- [Gerenciar localidades](./manage-locales.mdx)
- [Configuração](./configuration.mdx)
- [Pacote Common e compatibilidade](./packages-common.mdx#compatibility)
- [Instalar plugins](./plugins-installation.mdx)
