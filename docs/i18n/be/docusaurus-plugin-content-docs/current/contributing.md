---
title: "Удзел у праекце"
description: "Знайдзіце і адрэдагуйце пераклады ў Mona, затым праверце змены перад стварэннем запыту на зліццё."
---

Дасылайце змены ў [рэпазіторый Mona](https://github.com/Laravel-Lang/mona). Mona захоўвае зыходныя шаблоны, агульныя пераклады і пакеты PHP у адным рэпазіторыі.

## Выпраўленне або даданне перакладу {#fix-or-add-a-translation}

1. Знайдзіце англійскую фразу ў каталогу `src/<Package>/source/` пакета. Напрыклад, зыходныя файлы фрэймворка Laravel і яго афіцыйных пакетаў знаходзяцца ў `src/Lang/source/`.
2. Адкрыйце `src/Library/locales/<locale>/default.json`. Знайдзіце англійскую фразу як ключ JSON і адрэдагуйце яе значэнне. Дадайце запіс, калі зыходная фраза ёсць у пакеце, а ў слоўніку яе няма.
3. Пакіньце зыходную фразу без змен і захавайце падстаноўкі `:count`, `:other`, `:attribute` і падобныя. Для ўбудаванага паведамлення праверкі выкарыстоўвайце той жа зыходны ключ у `inline.json` і сфармулюйце паведамленне без назвы поля.
4. Калі пераклад наўмысна супадае з англійскай фразай, дадайце яе ў `_settings.json` лакалі ў раздзел `excludes`. Toolkit выкарыстоўвае гэты спіс для справаздачы пра стан перакладу.
5. Стварыце запыт на зліццё з кодам лакалі ў загалоўку, напрыклад `[fr]: Updated validation messages`.

Калі знайшлі памылку друку ў зыходнай фразе з іншага праекта, паведаміце пра яе ў тым праекце, каб фразу і яе пошукавы ключ абнавілі разам.

Напрыклад, запіс французскага перакладу па змаўчанні ў `src/Library/locales/fr/default.json` выглядае так:

```json
{
  "The :attribute field is invalid.": "Le champ :attribute est invalide."
}
```

Адпаведны запіс для ўбудаванага паведамлення ў `src/Library/locales/fr/inline.json`:

```json
{
  "The :attribute field is invalid.": "Ce champ est invalide."
}
```

Каб абнавіць назвы краін, валют або лакаляў, адрэдагуйце слоўнік у каталогу `data/` адпаведнага пакета. Гл. [Лакалі і перакладзеныя назвы](./locales.md).

## Прапанова новай лакалі {#propose-a-locale}

Праверце [кананічныя коды лакаляў](./available-locales.md). Каб прапанаваць новы код, стварыце [абмеркаванне ў Laravel-Lang](https://github.com/orgs/Laravel-Lang/discussions/new?category=ideas&title=I%20want%20to%20add%20a%20new%20localization). Toolkit можа [стварыць каталогі](./toolkit.md#manage-locale-directories) для кодаў, якія ўжо ёсць у спісе.

## Лакальныя праверкі {#run-local-checks}

Усталюйце залежнасці з кораня рэпазіторыя. Калі вы змянілі слоўнік, праверце фармат JSON і стан перакладу. Каманда `status` запісвае справаздачы для перададзенага праекта.

```bash
composer install
php src/Toolkit/bin/toolkit status src/Library
```

Для змен у PHP запусціце `composer test` і `composer test:types`. Скрыпт `composer style` выпраўляе фарматаванне і стыль кода.

Для змен у маніфесце выканайце тую ж праверку па схеме, што і ў CI.

```bash
npx --yes ajv-cli@5.0.0 validate --spec=draft2020 -s resources/laravel-lang.schema.json -d 'src/*/laravel_lang.json'
```

Фармат маніфеста апісаны ў раздзеле [Плагіны перакладаў](./plugins.md), а каманды абслугоўвання рэпазіторыя — у раздзеле [Toolkit](./toolkit.md).
