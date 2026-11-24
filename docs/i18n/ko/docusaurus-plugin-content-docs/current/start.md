---
title: 시작하기
description: Laravel-Lang을 설치하고 첫 Laravel 번역을 게시합니다.
---

## 빠른 시작 {#quick-start}

```bash
composer require laravel-lang/common
php artisan lang:add fr
```

```php
__('auth.failed', locale: 'fr');
// Ces identifiants ne correspondent pas à nos enregistrements.
```

- [번역 관리](./translations.md) 및 [지원 로캘 살펴보기](./available-locales.md)
- [현지화 설정하기](./configure.md)
- [호환성 확인하기](./release-notes.md#versioning-and-compatibility) 또는 [기존 설치 업그레이드하기](./upgrade.md)
