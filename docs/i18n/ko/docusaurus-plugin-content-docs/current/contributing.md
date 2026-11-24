---
title: 기여하기
description: Mona에서 번역을 찾아 수정하고 풀 리퀘스트를 열기 전에 변경 사항을 확인합니다.
---

변경 사항은 [Mona 저장소](https://github.com/Laravel-Lang/mona)에 제출하세요. Mona는 소스 템플릿, 공용 번역, PHP 패키지를 한 저장소에서 관리합니다.

## 번역 수정 또는 추가 {#fix-or-add-a-translation}

1. 팩의 `src/<Package>/source/` 디렉터리에서 영어 문구를 찾습니다. Laravel Framework와 Laravel 공식 패키지의 소스는 `src/Lang/source/`에 있습니다.
2. `src/Library/locales/<locale>/default.json`을 엽니다. 영어 문구와 일치하는 JSON 키를 찾고 값을 수정합니다. 원문이 팩에 있지만 사전에 없으면 항목을 추가하세요.
3. 원문을 그대로 두고 `:count`, `:other`, `:attribute` 같은 자리표시자를 보존합니다. 인라인 유효성 검사 메시지는 `inline.json`의 동일한 원문 키에 필드 이름을 쓰지 않는 표현으로 작성하세요.
4. 번역문을 의도적으로 영어 원문과 같게 두었다면 해당 로캘의 `_settings.json`에 있는 `excludes`에 문구를 추가합니다. Toolkit은 번역 상태를 생성할 때 이 목록을 사용합니다.
5. 제목에 로캘 코드가 있는 풀 리퀘스트를 엽니다. 예시는 `[fr]: Updated validation messages`입니다.

상위 프로젝트의 원문에 오타가 있으면 원문과 조회 키를 함께 수정할 수 있도록 그 프로젝트에 신고하세요.

다음은 `src/Library/locales/fr/default.json`에 있는 프랑스어 기본 항목의 예입니다.

```json
{
  "The :attribute field is invalid.": "Le champ :attribute est invalide."
}
```

`src/Library/locales/fr/inline.json`의 대응하는 인라인 항목은 아래와 같습니다.

```json
{
  "The :attribute field is invalid.": "Ce champ est invalide."
}
```

국가, 통화 또는 로캘의 이름을 바꾸려면 해당 패키지의 `data/` 디렉터리에 있는 사전을 수정하세요. [로캘과 번역된 이름](./locales.md)을 참조하세요.

## 로캘 제안하기 {#propose-a-locale}

[표준 로캘 코드](./available-locales.md)를 확인하세요. 새 코드를 제안하려면 [Laravel-Lang 토론](https://github.com/orgs/Laravel-Lang/discussions/new?category=ideas&title=I%20want%20to%20add%20a%20new%20localization)을 엽니다. 목록에 이미 있는 코드의 [디렉터리는 Toolkit으로 생성](./toolkit.md#manage-locale-directories)할 수 있습니다.

## 로컬 검사 실행하기 {#run-local-checks}

저장소 루트에서 의존성을 설치합니다. 사전을 변경했다면 JSON을 검증하고 번역 상태를 확인하세요. `status`는 인수로 전달한 프로젝트의 보고서를 작성합니다.

```bash
composer install
php src/Toolkit/bin/toolkit status src/Library
```

PHP 변경 사항은 `composer test`와 `composer test:types`로 검사합니다. `composer style` 스크립트는 서식을 정리하고 코드 스타일을 수정합니다.

매니페스트 변경 사항은 CI에서 쓰는 것과 동일한 스키마 검사로 확인하세요.

```bash
npx --yes ajv-cli@5.0.0 validate --spec=draft2020 -s resources/laravel-lang.schema.json -d 'src/*/laravel_lang.json'
```

매니페스트 형식은 [번역 플러그인](./plugins.md), 저장소 유지보수 명령은 [Toolkit](./toolkit.md)을 참조하세요.
