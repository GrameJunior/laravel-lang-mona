---
title: 이전 버전 문서
slug: /
description: Mona 이전에 릴리스된 Laravel-Lang 패키지의 문서입니다.
---

개별 버전으로 배포된 이전 Laravel-Lang 패키지를 사용하는 경우 이 문서를 참조하세요. 애플리케이션을 통합 릴리스 계열로 옮기려면 [Mona로 업그레이드하기](/upgrade)를 참조하세요.

## 빠른 시작 {#quick-start}

```bash
composer require laravel-lang/common:^6.9
php artisan lang:add fr
```

```php
__('Remember me', locale: 'fr');
// Se souvenir de moi
```

- [시작하기](./basic-usage.mdx) 및 [지원 로캘](./available-locales-list.mdx)
- [로캘 관리](./manage-locales.mdx)
- [설정](./configuration.mdx)
- [Common 패키지와 호환성](./packages-common.mdx#compatibility)
- [플러그인 설치](./plugins-installation.mdx)
