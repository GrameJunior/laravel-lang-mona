---
title: Участие в проекте
description: Найдите и отредактируйте переводы в Mona и проверьте изменения перед открытием пул-реквеста.
---

Отправляйте изменения в [репозиторий Mona](https://github.com/Laravel-Lang/mona). Mona хранит исходные шаблоны, общие переводы и PHP-пакеты в одном репозитории.

## Исправление или добавление перевода {#fix-or-add-a-translation}

1. Найдите английскую фразу в каталоге пакета `src/<Package>/source/`. Например, источники Laravel Framework и официальных пакетов Laravel находятся в `src/Lang/source/`.
2. Откройте `src/Library/locales/<locale>/default.json`. Найдите JSON-ключ с английской фразой и измените его значение. Добавьте запись, если исходная фраза есть в пакете, но отсутствует в словаре.
3. Сохраняйте исходную фразу и её подстановки, например `:count`, `:other` и `:attribute`. Для сообщения валидации без имени поля используйте тот же исходный ключ в `inline.json` и подберите формулировку без названия поля.
4. Если перевод намеренно совпадает с английской фразой, добавьте её в список `excludes` файла `_settings.json` этой локали. Toolkit учитывает список при составлении отчёта о состоянии переводов.
5. Откройте пул-реквест с кодом локали в заголовке, например `[fr]: Updated validation messages`.

Если нашли опечатку в исходной фразе из другого проекта, сообщите о ней в том проекте, чтобы исходник и ключ поиска обновились вместе.

Например, стандартная запись на французском в `src/Library/locales/fr/default.json` выглядит так:

```json
{
  "The :attribute field is invalid.": "Le champ :attribute est invalide."
}
```

Соответствующая запись без имени поля в `src/Library/locales/fr/inline.json` выглядит так:

```json
{
  "The :attribute field is invalid.": "Ce champ est invalide."
}
```

Чтобы обновить названия стран, валют или локалей, отредактируйте словарь в каталоге `data/` соответствующего пакета. См. [Локали и переведённые названия](./locales.md).

## Как предложить локаль {#propose-a-locale}

Проверьте [канонические коды локалей](./available-locales.md). Чтобы предложить новый код, откройте [обсуждение в Laravel-Lang](https://github.com/orgs/Laravel-Lang/discussions/new?category=ideas&title=I%20want%20to%20add%20a%20new%20localization). Toolkit умеет [создавать каталоги](./toolkit.md#manage-locale-directories) для кодов, которые уже есть в списке.

## Локальные проверки {#run-local-checks}

Установите зависимости из корня репозитория. Если меняли словарь, проверьте корректность JSON и состояние переводов. Команда `status` создаёт отчёты для указанного проекта.

```bash
composer install
php src/Toolkit/bin/toolkit status src/Library
```

Если меняли PHP, выполните `composer test` и `composer test:types`. Скрипт `composer style` форматирует код и исправляет нарушения стиля.

Если меняли манифест, выполните ту же проверку схемы, что и в CI.

```bash
npx --yes ajv-cli@5.0.0 validate --spec=draft2020 -s resources/laravel-lang.schema.json -d 'src/*/laravel_lang.json'
```

Формат манифеста описан в разделе [Плагины переводов](./plugins.md), а команды для работы с репозиторием — в разделе [Toolkit](./toolkit.md).
