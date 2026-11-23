---
title: Model translations
description: Store, read, and query localized Eloquent attributes with Laravel-Lang Models.
---

`laravel-lang/models` adds translated attributes to an existing Eloquent model. Each translation row stores a locale and that locale's attribute values. The package is included in Common and requires PHP Intl.

```bash
composer require "laravel-lang/models:^20.0"
```

## Add translations to a model

For an existing `App\Models\Article` model with a base table migration, run:

```bash
php artisan make:model-translation 'App\Models\Article' --columns=title --columns=description
php artisan migrate
```

The generator adds the `LaravelLang\Models\HasTranslations` trait to the base model and creates the companion `ArticleTranslation` model, a migration, and an IDE helper. The migration creates a foreign key back to the article, a unique `(item_id, locale)` pair, and nullable string columns for the translated values. If you already store these values in the base table, migrate them to translation rows before relying on the new model's accessors.

If the model is outside `app/Models`, add its directory to [`localization_models.directories`](./configure.md) before running the generator.

## Write and read translated attributes

With [French installed](./translations.md), you can store a translation by passing its canonical code, alias, `Locale` enum case, or `LocaleData`. Use the base model's `save()` to persist changes.

```php
use App\Models\Article;
use LaravelLang\LocaleList\Locale;

$article = Article::create(['title' => 'First article']);
$article->setTranslation('title', 'Premier article', Locale::French)->save();

$article->title;                         // current locale, then fallback for a null value
$article->getTranslation('title', 'fr');  // 'Premier article'
```

An explicit locale reads that locale's value. Without a locale argument, writes use the current locale and reads fall back to the configured Laravel fallback locale when the current value is `null`. Aliases resolve to canonical codes for lookup and storage. An explicit unknown or uninstalled locale raises `LaravelLang\Exceptions\UnknownLocaleException`.

Use `setTranslations()` for several values of one field. You can also pass a locale-keyed map to `create()` or `fill()` for each translated field. The example assumes English and French are installed.

```php
$article->setTranslations('title', [
    'en' => 'First article',
    'fr' => 'Premier article',
])->save();
```

Use `isTranslatable('title')` to check the model's configured fields and `hasTranslated('title', 'fr')` to check for a filled value. `forgetTranslation('fr')` deletes the French row, and `forgetAllTranslations()` deletes all rows for the model.

## Query translations

`translated()`, `whereTranslation()`, and `whereTranslationLike()` query through the `translations()` relation. By default this relation filters rows to the current and fallback locales. To query other locales through these scopes, set `localization_models.filter` to `false`. To read all rows for a single model, use `translationsRaw()`.

With French as the current locale, query and sort by the French title:

```php
use App\Models\Article;

$articles = Article::query()
    ->whereTranslationLike('title', 'Premier%', 'fr')
    ->orderByTranslation('title', 'asc', 'fr')
    ->get();
```

## Observe changes and refresh IDE helpers

The `LaravelLang\Models\Events` namespace contains `TranslationHasBeenSetEvent`, `TranslationHasBeenForgetEvent`, and `AllTranslationsHasBeenForgetEvent`. The set event contains the model, column, optional locale alias, old value, and new value. It fires during attribute assignment before the subsequent save.

Run `php artisan lang:models-helper` to regenerate helpers for all discovered models. Their default directory is `vendor/_laravel_lang`, controlled by `localization_models.helpers`.
