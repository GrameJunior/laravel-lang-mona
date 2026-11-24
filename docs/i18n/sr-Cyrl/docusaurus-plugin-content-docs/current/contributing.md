---
title: Доприноси пројекту
description: Пронађите и измените преводе у Mona, а затим проверите измене пре отварања захтева за повлачење.
---

Пошаљите измене у [репозиторијум Mona](https://github.com/Laravel-Lang/mona). Mona чува изворне шаблоне, заједничке преводе и PHP пакете у једном репозиторијуму.

## Исправка или додавање превода {#fix-or-add-a-translation}

1. Пронађите енглески израз у директоријуму `src/<Package>/source/` одговарајућег пакета. Извори за Laravel Framework и званичне Laravel пакете, на пример, налазе се у `src/Lang/source/`.
2. Отворите `src/Library/locales/<locale>/default.json`. Пронађите енглески израз као JSON кључ и измените његову вредност. Додајте унос ако је изворни израз присутан у пакету, али недостаје у речнику.
3. Сачувајте изворни израз и маркере као што су `:count`, `:other` и `:attribute`. За поруку валидације без назива поља користите исти изворни кључ у `inline.json` и формулишите поруку тако да не помиње поље.
4. Ако се превод намерно поклапа са енглеским изразом, додајте тај израз под `excludes` у датотеци `_settings.json` локала. Toolkit користи овај списак при генерисању статуса превода.
5. Отворите захтев за повлачење са кодом локала у наслову, на пример `[fr]: Updated validation messages`.

Ако пронађете словну грешку у изворном изразу другог пројекта, пријавите је у том пројекту како би извор и кључ за његово проналажење били ажурирани заједно.

Унос за француски у `src/Library/locales/fr/default.json`, на пример, изгледа овако.

```json
{
  "The :attribute field is invalid.": "Le champ :attribute est invalide."
}
```

Одговарајући унос без назива поља у `src/Library/locales/fr/inline.json` изгледа овако.

```json
{
  "The :attribute field is invalid.": "Ce champ est invalide."
}
```

Да ажурирате називе земаља, валута или локала, измените речник у директоријуму `data/` одговарајућег пакета. Погледајте [локале и преведене називе](./locales.md).

## Предлагање локала {#propose-a-locale}

Проверите [канонске кодове локала](./available-locales.md). Да предложите нови код, отворите [дискусију у Laravel-Lang](https://github.com/orgs/Laravel-Lang/discussions/new?category=ideas&title=I%20want%20to%20add%20a%20new%20localization). Toolkit може да [направи директоријуме](./toolkit.md#manage-locale-directories) за кодове који су већ на списку.

## Локалне провере {#run-local-checks}

Инсталирајте зависности из корена репозиторијума. Ако мењате речник, проверите исправност JSON-а и статус превода. Команда `status` уписује извештаје за пројекат који сте јој проследили.

```bash
composer install
php src/Toolkit/bin/toolkit status src/Library
```

За измене у PHP-у покрените `composer test` и `composer test:types`. Скрипта `composer style` примењује форматирање и исправке стила кода.

За измене манифеста покрените исту проверу шеме која се користи у CI-ју.

```bash
npx --yes ajv-cli@5.0.0 validate --spec=draft2020 -s resources/laravel-lang.schema.json -d 'src/*/laravel_lang.json'
```

Формат манифеста описан је у [додацима за преводе](./plugins.md), а команде за одржавање репозиторијума у [Toolkit-у](./toolkit.md).
