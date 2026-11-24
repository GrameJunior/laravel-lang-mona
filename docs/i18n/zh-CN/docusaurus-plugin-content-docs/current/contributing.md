---
title: 参与贡献
description: 查找并编辑 Mona 中的译文，提交拉取请求前验证更改。
---

请向 [Mona 仓库](https://github.com/Laravel-Lang/mona)提交更改。Mona 将源模板、共享译文和 PHP 包集中在同一个仓库中。

## 修正或添加译文 {#fix-or-add-a-translation}

1. 在翻译包的 `src/<Package>/source/` 目录中找到英文短语。例如，Laravel 框架和官方包的源文件位于 `src/Lang/source/`。
2. 打开 `src/Library/locales/<locale>/default.json`，找到以该英文短语为键的 JSON 条目并修改其值。如果翻译包中已有源短语，但词典中尚无对应键，请添加条目。
3. 保持源短语不变，并保留 `:count`、`:other` 和 `:attribute` 等占位符。编写行内验证消息时，在 `inline.json` 中使用相同的源键，并采用无需提及字段名称的措辞。
4. 如果有意让译文与英文短语一致，请将该短语添加到此语言区域 `_settings.json` 的 `excludes` 列表中。Toolkit 会在生成翻译状态时读取此列表。
5. 提交拉取请求，在标题中注明语言区域代码，例如 `[fr]: Updated validation messages`。

如果发现上游项目的源短语有拼写错误，请向上游报告，以便同时更正源文本和查找键。

例如，`src/Library/locales/fr/default.json` 中的法语默认条目如下。

```json
{
  "The :attribute field is invalid.": "Le champ :attribute est invalide."
}
```

它在 `src/Library/locales/fr/inline.json` 中对应的行内条目如下。

```json
{
  "The :attribute field is invalid.": "Ce champ est invalide."
}
```

要更新国家或地区、货币或语言区域名称，请编辑相应包的 `data/` 目录下的词典。请参阅[语言区域和译名](./locales.md)。

## 提议添加语言区域 {#propose-a-locale}

先查看[规范语言区域代码](./available-locales.md)。如果要提议添加新代码，请在 [Laravel-Lang 讨论区](https://github.com/orgs/Laravel-Lang/discussions/new?category=ideas&title=I%20want%20to%20add%20a%20new%20localization)发帖。对于列表中已有的代码，Toolkit 可以[创建目录](./toolkit.md#manage-locale-directories)。

## 在本地运行检查 {#run-local-checks}

在仓库根目录安装依赖。修改词典后，请验证 JSON 并检查翻译状态。`status` 会为传入的项目生成报告。

```bash
composer install
php src/Toolkit/bin/toolkit status src/Library
```

修改 PHP 代码后，请运行 `composer test` 和 `composer test:types`。`composer style` 脚本会应用格式化和代码风格修复。

修改清单后，请运行 CI 所用的同一项架构验证。

```bash
npx --yes ajv-cli@5.0.0 validate --spec=draft2020 -s resources/laravel-lang.schema.json -d 'src/*/laravel_lang.json'
```

清单格式请参阅[翻译插件](./plugins.md)，仓库维护命令请参阅 [Toolkit](./toolkit.md)。
