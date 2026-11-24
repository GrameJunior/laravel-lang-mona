---
title: 旧版ドキュメント
slug: /
description: Mona より前にリリースされた Laravel-Lang パッケージのドキュメントです。
---

以前のように個別にバージョン管理された Laravel-Lang パッケージを使用している環境向けのドキュメントです。統一されたリリース系列へ移行するには、[Mona へのアップグレード](/upgrade)を参照してください。

## クイックスタート {#quick-start}

```bash
composer require laravel-lang/common:^6.9
php artisan lang:add fr
```

```php
__('Remember me', locale: 'fr');
// Se souvenir de moi
```

- [はじめに](./basic-usage.mdx)、[利用可能なロケール](./available-locales-list.mdx)
- [ロケールの管理](./manage-locales.mdx)
- [設定](./configuration.mdx)
- [Common パッケージと互換性](./packages-common.mdx#compatibility)
- [プラグインのインストール](./plugins-installation.mdx)
