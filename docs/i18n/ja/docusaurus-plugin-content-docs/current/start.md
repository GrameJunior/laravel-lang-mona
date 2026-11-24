---
title: はじめに
description: Laravel-Lang をインストールし、Laravel に最初の翻訳を公開します。
---

## クイックスタート {#quick-start}

```bash
composer require laravel-lang/common
php artisan lang:add fr
```

```php
__('auth.failed', locale: 'fr');
// Ces identifiants ne correspondent pas à nos enregistrements.
```

- [翻訳を管理する](./translations.md)、[利用可能なロケールを確認する](./available-locales.md)
- [ローカライズを設定する](./configure.md)
- [互換性を確認する](./release-notes.md#versioning-and-compatibility)、[既存の環境をアップグレードする](./upgrade.md)
