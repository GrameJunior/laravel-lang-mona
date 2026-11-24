---
title: Contribuir
description: Encontre e edite traduções no Mona e verifique as alterações antes de abrir um pull request.
---

Envie alterações ao [repositório do Mona](https://github.com/Laravel-Lang/mona). O Mona reúne os modelos de origem, as traduções compartilhadas e os pacotes PHP em um só repositório.

## Corrigir ou adicionar uma tradução {#fix-or-add-a-translation}

1. Encontre a frase em inglês no diretório `src/<Package>/source/` do pacote. Por exemplo, as fontes do Laravel Framework e dos pacotes oficiais do Laravel ficam em `src/Lang/source/`.
2. Abra `src/Library/locales/<locale>/default.json`. Procure a frase em inglês como chave JSON e edite seu valor. Adicione uma entrada se a frase de origem estiver no pacote e não constar no dicionário.
3. Mantenha a frase de origem e preserve marcadores como `:count`, `:other` e `:attribute`. Para uma mensagem de validação inline, use a mesma chave de origem em `inline.json` com uma redação que dispense o nome do campo.
4. Se uma tradução for intencionalmente igual à frase em inglês, adicione a frase a `excludes` no `_settings.json` da localidade. O Toolkit usa essa lista ao gerar o status das traduções.
5. Abra um pull request com o código de localidade no título, como `[fr]: Updated validation messages`.

Se encontrar um erro de digitação em uma frase de um projeto de origem, comunique-o a esse projeto para que a frase e sua chave de consulta sejam atualizadas juntas.

Veja, por exemplo, a entrada francesa padrão em `src/Library/locales/fr/default.json`.

```json
{
  "The :attribute field is invalid.": "Le champ :attribute est invalide."
}
```

A entrada inline correspondente fica em `src/Library/locales/fr/inline.json`.

```json
{
  "The :attribute field is invalid.": "Ce champ est invalide."
}
```

Para atualizar nomes de países, moedas ou localidades, edite o dicionário em `data/` no pacote correspondente. Consulte [localidades e nomes traduzidos](./locales.md).

## Propor uma localidade {#propose-a-locale}

Consulte os [códigos canônicos de localidades](./available-locales.md). Para propor um código novo, abra uma [discussão no Laravel-Lang](https://github.com/orgs/Laravel-Lang/discussions/new?category=ideas&title=I%20want%20to%20add%20a%20new%20localization). O Toolkit pode [criar diretórios](./toolkit.md#manage-locale-directories) para códigos que já constam na lista.

## Executar verificações locais {#run-local-checks}

Instale as dependências na raiz do repositório. Para mudanças em um dicionário, valide seu JSON e verifique o status das traduções. `status` grava relatórios para o projeto informado.

```bash
composer install
php src/Toolkit/bin/toolkit status src/Library
```

Para alterações em PHP, execute `composer test` e `composer test:types`. O script `composer style` aplica correções de formatação e estilo de código.

Para alterações em manifestos, execute a mesma verificação de esquema usada na CI.

```bash
npx --yes ajv-cli@5.0.0 validate --spec=draft2020 -s resources/laravel-lang.schema.json -d 'src/*/laravel_lang.json'
```

Consulte [plugins de tradução](./plugins.md) para ver o formato do manifesto e o [Toolkit](./toolkit.md) para conhecer os comandos de manutenção do repositório.
