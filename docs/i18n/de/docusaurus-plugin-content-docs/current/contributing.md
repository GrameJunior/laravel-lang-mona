---
title: "Mitwirken"
description: "Finde und bearbeite Übersetzungen in Mona und überprüfe die Änderungen, bevor du einen Pull Request erstellst."
---

Reiche Änderungen im [Mona-Repository](https://github.com/Laravel-Lang/mona) ein. Mona verwaltet die Quellvorlagen, gemeinsamen Übersetzungen und PHP-Pakete in einem Repository.

## Eine Übersetzung korrigieren oder hinzufügen {#fix-or-add-a-translation}

1. Suche die englische Formulierung im Verzeichnis `src/<Package>/source/` des Pakets. Die Quellen für Laravel Framework und die offiziellen Laravel-Pakete liegen beispielsweise unter `src/Lang/source/`.
2. Öffne `src/Library/locales/<locale>/default.json`. Suche den englischen Text als JSON-Schlüssel und bearbeite dessen Wert. Füge einen Eintrag hinzu, wenn der Quelltext im Paket vorkommt und im Wörterbuch fehlt.
3. Behalte den Quelltext und Platzhalter wie `:count`, `:other` und `:attribute` bei. Verwende für eine Inline-Validierungsmeldung denselben Quellschlüssel in `inline.json` und formuliere den Text ohne Feldnamen.
4. Wenn eine Übersetzung absichtlich dem englischen Text entspricht, ergänze ihn in `_settings.json` der Locale unter `excludes`. Toolkit verwendet diese Liste, um den Übersetzungsstatus zu ermitteln.
5. Erstelle einen Pull Request mit dem Locale-Code im Titel, etwa `[fr]: Updated validation messages`.

Wenn du einen Tippfehler im Quelltext eines vorgelagerten Projekts entdeckst, melde ihn dort, damit der Quelltext und der zugehörige Suchschlüssel gemeinsam aktualisiert werden können.

Der französische Standardeintrag in `src/Library/locales/fr/default.json` sieht beispielsweise so aus.

```json
{
  "The :attribute field is invalid.": "Le champ :attribute est invalide."
}
```

So sieht der dazugehörige Inline-Eintrag in `src/Library/locales/fr/inline.json` aus.

```json
{
  "The :attribute field is invalid.": "Ce champ est invalide."
}
```

Um Länder-, Währungs- oder Locale-Namen zu aktualisieren, bearbeite das Wörterbuch im Verzeichnis `data/` des jeweiligen Pakets. Siehe [Locales und übersetzte Namen](./locales.md).

## Eine Locale vorschlagen {#propose-a-locale}

Sieh dir die [kanonischen Locale-Codes](./available-locales.md) an. Um einen neuen Code vorzuschlagen, eröffne eine [Diskussion zu Laravel-Lang](https://github.com/orgs/Laravel-Lang/discussions/new?category=ideas&title=I%20want%20to%20add%20a%20new%20localization). Für bereits gelistete Codes kann Toolkit [Verzeichnisse erstellen](./toolkit.md#manage-locale-directories).

## Lokale Prüfungen ausführen {#run-local-checks}

Installiere die Abhängigkeiten vom Stammverzeichnis des Repositorys aus. Validiere bei Wörterbuchänderungen das JSON und prüfe den Übersetzungsstatus. `status` schreibt Berichte für das Projekt, das du übergibst.

```bash
composer install
php src/Toolkit/bin/toolkit status src/Library
```

Führe bei PHP-Änderungen `composer test` und `composer test:types` aus. Das Skript `composer style` korrigiert Formatierung und Codestil.

Führe nach Manifeständerungen dieselbe Schemaprüfung wie in der CI aus.

```bash
npx --yes ajv-cli@5.0.0 validate --spec=draft2020 -s resources/laravel-lang.schema.json -d 'src/*/laravel_lang.json'
```

Das Manifestformat findest du unter [Übersetzungs-Plugins](./plugins.md), die Befehle zur Pflege des Repositorys unter [Toolkit](./toolkit.md).
