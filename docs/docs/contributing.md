---
title: Contributing
description: Find and edit translations in Mona, then verify the changes before opening a pull request.
---

Send changes to the [Mona repository](https://github.com/Laravel-Lang/mona). Mona keeps the source templates, shared translations, and PHP packages in one repository.

## Fix or add a translation

1. Find the English phrase in the pack's `src/<Package>/source/` directory. For example, Laravel Framework and first-party Laravel package sources are under `src/Lang/source/`.
2. Open `src/Library/locales/<locale>/default.json`. Find the English phrase as a JSON key and edit its value. Add an entry if the source phrase is present in the pack and absent from the dictionary.
3. Keep the source phrase unchanged and preserve placeholders such as `:count`, `:other`, and `:attribute`. For an inline validation message, use the same source key in `inline.json` with wording that avoids naming the field.
4. If a translation intentionally matches the English phrase, add the phrase to the locale's `_settings.json` under `excludes`. Toolkit uses this list when generating translation status.
5. Open a pull request with the locale code in the title, such as `[fr]: Updated validation messages`.

If you find a typo in a source phrase from an upstream project, report it there so the source and its lookup key can be updated together.

For example, the French default entry in `src/Library/locales/fr/default.json` is:

```json
{
  "The :attribute field is invalid.": "Le champ :attribute est invalide."
}
```

The corresponding inline entry in `src/Library/locales/fr/inline.json` is:

```json
{
  "The :attribute field is invalid.": "Ce champ est invalide."
}
```

To update country, currency, or locale names, edit the dictionary under the corresponding package's `data/` directory. See [locales and translated names](./locales.md).

## Propose a locale

Check the [canonical locale codes](./available-locales.md). To propose a new code, open a [Laravel-Lang discussion](https://github.com/orgs/Laravel-Lang/discussions/new?category=ideas&title=I%20want%20to%20add%20a%20new%20localization). Toolkit can [create directories](./toolkit.md#manage-locale-directories) for codes already in the list.

## Run local checks

From the repository root, install dependencies. For a dictionary change, validate your JSON and check translation status. `status` writes reports for the project you pass in.

```bash
composer install
php src/Toolkit/bin/toolkit status src/Library
```

For PHP changes, run `composer test` and `composer test:types`. The `composer style` script applies formatting and code-style fixes.

For manifest changes, run the same schema check used in CI.

```bash
npx --yes ajv-cli@5.0.0 validate --spec=draft2020 -s resources/laravel-lang.schema.json -d 'src/*/laravel_lang.json'
```

See [translation plugins](./plugins.md) for the manifest format and [Toolkit](./toolkit.md) for the repository maintenance commands.
