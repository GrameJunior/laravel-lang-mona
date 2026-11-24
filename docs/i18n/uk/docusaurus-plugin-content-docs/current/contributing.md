---
title: Як долучитися
description: Знайдіть і відредагуйте переклади в Mona, а потім перевірте зміни перед створенням запиту на злиття.
---

Надсилайте зміни до [репозиторію Mona](https://github.com/Laravel-Lang/mona). Mona зберігає вихідні шаблони, спільні переклади й пакети PHP в одному репозиторії.

## Виправлення або додавання перекладу {#fix-or-add-a-translation}

1. Знайдіть англійську фразу в каталозі пакета `src/<Package>/source/`. Наприклад, вихідні файли Laravel Framework і офіційних пакетів Laravel містяться в `src/Lang/source/`.
2. Відкрийте `src/Library/locales/<locale>/default.json`. Знайдіть англійську фразу як ключ JSON і змініть її значення. Якщо фраза є в пакеті, але її немає у словнику, додайте запис.
3. Збережіть вихідну фразу без змін, зокрема заповнювачі на кшталт `:count`, `:other` і `:attribute`. Для повідомлення перевірки без назви поля використовуйте той самий вихідний ключ в `inline.json`, але сформулюйте повідомлення без назви поля.
4. Якщо переклад навмисно збігається з англійською фразою, додайте її до `excludes` у файлі `_settings.json` відповідної локалі. Toolkit використовує цей список для звітів про стан перекладів.
5. Створіть запит на злиття, зазначивши код локалі в заголовку, наприклад `[fr]: Updated validation messages`.

Якщо ви знайшли друкарську помилку у вихідній фразі іншого проєкту, повідомте про неї в його репозиторії, щоб оновити фразу разом із ключем пошуку.

Наприклад, типовий французький запис у `src/Library/locales/fr/default.json` має такий вигляд.

```json
{
  "The :attribute field is invalid.": "Le champ :attribute est invalide."
}
```

Відповідний запис для повідомлень без назви поля у `src/Library/locales/fr/inline.json` має такий вигляд.

```json
{
  "The :attribute field is invalid.": "Ce champ est invalide."
}
```

Щоб оновити назви країн, валют або локалей, відредагуйте словник у каталозі `data/` відповідного пакета. Див. [Локалі й перекладені назви](./locales.md).

## Пропозиція нової локалі {#propose-a-locale}

Перевірте [канонічні коди локалей](./available-locales.md). Щоб запропонувати новий код, відкрийте [обговорення в Laravel-Lang](https://github.com/orgs/Laravel-Lang/discussions/new?category=ideas&title=I%20want%20to%20add%20a%20new%20localization). Toolkit може [створити каталоги](./toolkit.md#manage-locale-directories) для кодів, які вже є в списку.

## Локальні перевірки {#run-local-checks}

Установіть залежності з кореня репозиторію. Якщо ви змінювали словник, перевірте його JSON і стан перекладів. Команда `status` записує звіти для вказаного проєкту.

```bash
composer install
php src/Toolkit/bin/toolkit status src/Library
```

Для змін у PHP виконайте `composer test` і `composer test:types`. Скрипт `composer style` форматує код та виправляє порушення стилю.

Якщо ви змінювали маніфест, перевірте його за тією самою схемою, що використовує CI.

```bash
npx --yes ajv-cli@5.0.0 validate --spec=draft2020 -s resources/laravel-lang.schema.json -d 'src/*/laravel_lang.json'
```

Формат маніфесту описано в розділі [Плагіни перекладу](./plugins.md), а команди обслуговування репозиторію — в розділі [Toolkit](./toolkit.md).
