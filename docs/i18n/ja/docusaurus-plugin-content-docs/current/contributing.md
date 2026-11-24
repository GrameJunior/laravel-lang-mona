---
title: コントリビュート
description: Mona で翻訳を探して編集し、変更を検証してからプルリクエストを作成します。
---

変更は [Mona リポジトリ](https://github.com/Laravel-Lang/mona)に送信してください。Mona は原文のテンプレート、共有翻訳、PHP パッケージを単一のリポジトリで管理しています。

## 翻訳を修正または追加する {#fix-or-add-a-translation}

1. パックの `src/<Package>/source/` ディレクトリで英語の文言を探します。たとえば、Laravel Framework と Laravel 公式パッケージの原文は `src/Lang/source/` 以下にあります。
2. `src/Library/locales/<locale>/default.json` を開き、英語の文言を JSON キーとして検索し、その値を編集します。原文がパックにあり辞書になければ、エントリを追加します。
3. 原文は維持し、`:count`、`:other`、`:attribute` などのプレースホルダーも保持します。インラインのバリデーションメッセージでは、`inline.json` の同じ原文キーにフィールド名を含めない文言を使用します。
4. 翻訳を意図的に英語の原文と同じにする場合は、そのロケールの `_settings.json` にある `excludes` に文言を追加します。Toolkit は翻訳状況の生成時にこの一覧を使用します。
5. タイトルにロケールコードを付けてプルリクエストを作成します。例は `[fr]: Updated validation messages` です。

上流プロジェクトの原文に誤字を見つけた場合は、原文と検索キーを同時に更新できるよう、上流に報告してください。

`src/Library/locales/fr/default.json` のフランス語の既定のエントリを例にします。

```json
{
  "The :attribute field is invalid.": "Le champ :attribute est invalide."
}
```

対応する `src/Library/locales/fr/inline.json` のインラインエントリを次に示します。

```json
{
  "The :attribute field is invalid.": "Ce champ est invalide."
}
```

国名、通貨名、ロケール名を更新するには、該当するパッケージの `data/` ディレクトリにある辞書を編集してください。[ロケールと翻訳済みの名称](./locales.md)も参照してください。

## ロケールを提案する {#propose-a-locale}

[正規ロケールコード](./available-locales.md)を確認してください。新しいコードは [Laravel-Lang のディスカッション](https://github.com/orgs/Laravel-Lang/discussions/new?category=ideas&title=I%20want%20to%20add%20a%20new%20localization)で提案できます。一覧にあるコードの[ディレクトリは Toolkit で作成](./toolkit.md#manage-locale-directories)できます。

## ローカルで検証する {#run-local-checks}

リポジトリのルートから依存関係をインストールします。辞書を変更した場合は、JSON を検証し、翻訳状況を確認します。`status` は渡されたプロジェクトのレポートを書き出します。

```bash
composer install
php src/Toolkit/bin/toolkit status src/Library
```

PHP を変更した場合は `composer test` と `composer test:types` を実行します。`composer style` スクリプトはフォーマットとコードスタイルを修正します。

マニフェストを変更した場合は CI と同じスキーマ検証を実行します。

```bash
npx --yes ajv-cli@5.0.0 validate --spec=draft2020 -s resources/laravel-lang.schema.json -d 'src/*/laravel_lang.json'
```

マニフェストの形式については[翻訳プラグイン](./plugins.md)、リポジトリのメンテナンスコマンドについては [Toolkit](./toolkit.md) を参照してください。
