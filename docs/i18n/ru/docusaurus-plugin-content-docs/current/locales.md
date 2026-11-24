---
title: Локали и названия
description: Узнайте, какие локали поддерживаются и установлены, разрешайте псевдонимы и получайте локализованные названия.
---

Laravel-Lang использует канонические коды вроде `en`, `fr` и `pt_BR` в Laravel, именах файлов переводов и строках переводов моделей. Псевдоним локали — имя, которое использует приложение, например `pt-br` в URL. Задавайте соответствия канонических кодов псевдонимам в [`localization_main.aliases`](./configure.md).

## Поиск поддерживаемых и установленных локалей {#find-supported-and-installed-locales}

`laravel-lang/locale-list` предоставляет перечисление со скалярными значениями `LaravelLang\LocaleList\Locale`. Его элементы соответствуют [поддерживаемым каноническим кодам](./available-locales.md).

```php
use LaravelLang\LocaleList\Locale;

Locale::French->value;        // 'fr'
Locale::tryFrom('pt_BR');     // Locale::PortugueseBrazil
Locale::values();            // array of canonical codes
Locale::collection();        // Collection of canonical codes
Locale::collection(false);   // Collection of enum cases
```

`laravel-lang/locales` добавляет фасад `LaravelLang\Locales\Facades\Locales`, который учитывает настройки приложения. Методы возвращают коллекции строк. Передайте `false`, чтобы получить канонические коды. По умолчанию для каждого кода используется настроенный псевдоним, если он есть.

| Метод | Что возвращает |
| --- | --- |
| `Locales::available()` | Все известные локали |
| `Locales::installed()` | Локали, найденные в языковых файлах приложения, а также защищённые локали |
| `Locales::uninstalled()` | Доступные локали, которые ещё не установлены |
| `Locales::protects()` | Фактическая текущая и резервная локали без дубликатов |

Поиск охватывает файлы и каталоги непосредственно в `lang_path()` и JSON-файлы, соответствующие шаблону `vendor/*/*.json` относительно этого пути. Имя файла или каталога должно преобразовываться в поддерживаемый код. Каталоги только с PHP-переводами в `lang/vendor/<package>/` не входят в эти шаблоны. Как опубликовать локаль, описано в разделе [Управление переводами](./translations.md).

## Чтение и установка локали приложения {#read-or-set-the-application-locale}

```php
use LaravelLang\Locales\Facades\Locales;

if (Locales::isInstalled('fr')) {
    Locales::set('fr');
}

$canonicalCode = Locales::current(false);
$displayCode = Locales::current();
$choices = Locales::data()->installed();
```

`set()` принимает код, псевдоним, элемент перечисления `Locale` или объект `LocaleData`. Если локаль установлена, метод задаёт её канонический код как локаль Laravel. Используйте `isAvailable()`, чтобы проверить поддержку кода, и `isInstalled()`, если важно наличие файлов или защиты. Метод `isProtected()` определяет текущую и резервную локали.

`current()` использует текущую локаль Laravel, если она распознана, иначе вызывает `fallback()`. `fallback()` разрешает настроенную в Laravel резервную локаль и использует `en`, если она неизвестна. Оба метода принимают аргумент `false` для получения канонического кода.

`Locales::info('fr')` возвращает объект `LaravelLang\LocaleNames\LocaleData` со свойствами `code`, `alias`, `native` и `localized`. Язык отображения соответствует текущей локали приложения. Чтобы получить сведения о текущей локали, вызовите метод без аргумента. Сведения об известной локали можно получить до её установки. Неизвестный код вызывает `LaravelLang\Exceptions\UnknownLocaleException`.

Используйте `fromAlias()`, чтобы получить канонический код по псевдониму, и `toAlias()`, чтобы получить псевдоним по каноническому коду. Эти методы возвращают неизвестные непустые строки как есть. Для проверки входных данных используйте `isAvailable()`.

## Поиск названий локалей, стран и валют {#look-up-locale-country-and-currency-names}

Пакеты `laravel-lang/locale-names`, `laravel-lang/country-names` и `laravel-lang/currency-names` предоставляют по сервису, который можно внедрить через контейнер Laravel. Все три пакета входят в Common.

```php
use LaravelLang\CountryNames\CountryNames;
use LaravelLang\CurrencyNames\CurrencyNames;
use LaravelLang\LocaleNames\LocaleNames;

$locale = app(LocaleNames::class)->one('de', 'fr');
$locale->localized; // 'Französisch'

$country = app(CountryNames::class)->one('de', 'fr');
$country->code;      // 'FR'
$country->localized; // 'Frankreich'

$currency = app(CurrencyNames::class)->one('de', 'fr');
$currency->code;    // 'EUR'
$currency->numeric; // 978

$nativeLocaleNames = app(LocaleNames::class)->all(null, \SortDirection::Ascending);
```

Первый параметр задаёт язык отображения. Передайте `null` для названий на языке самой локали. Второй параметр `one()` указывает локаль записи, например `fr` в примерах выше. При поиске названий стран и валют возвращаемый объект содержит код ISO. Оба параметра принимают канонические коды, настроенные псевдонимы и элементы перечисления `Locale`.

`all($forLocale)` возвращает коллекцию с каноническими кодами локалей в качестве ключей. Передайте `SortDirection::Ascending` или `SortDirection::Descending` для сортировки по свойству `localized`. Каждое значение представляет собой объект только для чтения. Значения для стран и валют также содержат `locale` соответствующей записи.
