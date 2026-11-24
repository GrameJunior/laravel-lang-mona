---
title: "Contribuer"
description: "Trouvez et modifiez les traductions dans Mona, puis vérifiez vos changements avant d’ouvrir une pull request."
---

Proposez vos changements dans le [dépôt Mona](https://github.com/Laravel-Lang/mona). Mona rassemble les modèles de fichiers sources, les traductions partagées et les paquets PHP dans un même dépôt.

## Corriger ou ajouter une traduction {#fix-or-add-a-translation}

1. Repérez l’expression anglaise dans le répertoire `src/<Package>/source/` du pack. Par exemple, les sources du framework Laravel et des paquets officiels Laravel se trouvent sous `src/Lang/source/`.
2. Ouvrez `src/Library/locales/<locale>/default.json`. Repérez l’expression anglaise utilisée comme clé JSON et modifiez sa valeur. Ajoutez une entrée si l’expression source figure dans le pack et manque au dictionnaire.
3. Gardez l’expression source intacte et préservez les paramètres comme `:count`, `:other` et `:attribute`. Pour un message de validation en ligne, utilisez la même clé source dans `inline.json`, avec une formulation qui évite de nommer le champ.
4. Si une traduction reprend volontairement l’expression anglaise, ajoutez l’expression sous `excludes` dans le fichier `_settings.json` de la langue. Toolkit utilise cette liste pour produire l’état des traductions.
5. Ouvrez une pull request dont le titre contient le code de langue, par exemple `[fr]: Updated validation messages`.

Si vous trouvez une faute dans une expression source qui vient d’un projet amont, signalez-la dans ce projet pour que l’expression et sa clé de recherche soient mises à jour ensemble.

Par exemple, l’entrée française par défaut dans `src/Library/locales/fr/default.json` est la suivante.

```json
{
  "The :attribute field is invalid.": "Le champ :attribute est invalide."
}
```

L’entrée correspondante dans `src/Library/locales/fr/inline.json` est la suivante.

```json
{
  "The :attribute field is invalid.": "Ce champ est invalide."
}
```

Pour mettre à jour les noms de pays, de devises ou de langues, modifiez le dictionnaire sous le répertoire `data/` du paquet correspondant. Consultez [Langues et libellés traduits](./locales.md).

## Proposer une langue {#propose-a-locale}

Consultez les [codes de langue canoniques](./available-locales.md). Pour proposer un nouveau code, ouvrez une [discussion Laravel-Lang](https://github.com/orgs/Laravel-Lang/discussions/new?category=ideas&title=I%20want%20to%20add%20a%20new%20localization). Toolkit peut [créer les répertoires](./toolkit.md#manage-locale-directories) pour les codes déjà répertoriés.

## Effectuer les vérifications locales {#run-local-checks}

Installez les dépendances depuis la racine du dépôt. Si vous modifiez un dictionnaire, validez le JSON et vérifiez l’état des traductions. `status` écrit les rapports pour le projet que vous lui indiquez.

```bash
composer install
php src/Toolkit/bin/toolkit status src/Library
```

Pour les modifications PHP, exécutez `composer test` et `composer test:types`. Le script `composer style` applique la mise en forme et corrige le style du code.

Pour les modifications de manifestes, exécutez la même validation du schéma que dans l’intégration continue.

```bash
npx --yes ajv-cli@5.0.0 validate --spec=draft2020 -s resources/laravel-lang.schema.json -d 'src/*/laravel_lang.json'
```

Consultez [Plugins de traduction](./plugins.md) pour connaître le format du manifeste et [Toolkit](./toolkit.md) pour les commandes de maintenance du dépôt.
