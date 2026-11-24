---
title: Cómo contribuir
description: Encuentra y edita traducciones en Mona y verifica los cambios antes de abrir una solicitud de cambios.
---

Envía los cambios al [repositorio Mona](https://github.com/Laravel-Lang/mona). Mona reúne las plantillas fuente, las traducciones compartidas y los paquetes PHP en un solo repositorio.

## Corregir o agregar una traducción {#fix-or-add-a-translation}

1. Busca la frase en inglés en el directorio `src/<Package>/source/` del paquete. Por ejemplo, las fuentes de Laravel Framework y de los paquetes oficiales de Laravel están en `src/Lang/source/`.
2. Abre `src/Library/locales/<locale>/default.json`. Busca la frase en inglés como clave JSON y edita su valor. Agrega una entrada si la frase original está en el paquete pero falta en el diccionario.
3. Conserva la frase original y los marcadores de posición como `:count`, `:other` y `:attribute`. Para un mensaje de validación en línea, usa la misma clave original en `inline.json` con una redacción que no nombre el campo.
4. Si una traducción coincide intencionalmente con la frase en inglés, agrega la frase a `excludes` en el archivo `_settings.json` de la configuración regional. Toolkit usa esta lista al generar el estado de las traducciones.
5. Abre una solicitud de cambios con el código de configuración regional en el título, por ejemplo `[fr]: Updated validation messages`.

Si encuentras un error de escritura en una frase de un proyecto del que provienen las traducciones, infórmalo allí para que la frase original y su clave de búsqueda se puedan actualizar juntas.

Por ejemplo, en `src/Library/locales/fr/default.json` se encuentra esta entrada predeterminada en francés.

```json
{
  "The :attribute field is invalid.": "Le champ :attribute est invalide."
}
```

Esta es la entrada en línea correspondiente de `src/Library/locales/fr/inline.json`.

```json
{
  "The :attribute field is invalid.": "Ce champ est invalide."
}
```

Para actualizar nombres de países, monedas o configuraciones regionales, edita el diccionario en el directorio `data/` del paquete correspondiente. Consulta [Configuraciones regionales y nombres traducidos](./locales.md).

## Proponer una configuración regional {#propose-a-locale}

Consulta los [códigos canónicos de configuración regional](./available-locales.md). Para proponer un código nuevo, abre un [debate de Laravel-Lang](https://github.com/orgs/Laravel-Lang/discussions/new?category=ideas&title=I%20want%20to%20add%20a%20new%20localization). Toolkit puede [crear directorios](./toolkit.md#manage-locale-directories) para los códigos que ya figuran en la lista.

## Ejecutar comprobaciones locales {#run-local-checks}

Instala las dependencias desde la raíz del repositorio. Si cambias un diccionario, valida el JSON y comprueba el estado de las traducciones. `status` escribe informes del proyecto que le indiques.

```bash
composer install
php src/Toolkit/bin/toolkit status src/Library
```

Si cambias código PHP, ejecuta `composer test` y `composer test:types`. El script `composer style` aplica correcciones de formato y estilo de código.

Si cambias un manifiesto, ejecuta la misma validación de esquema que se usa en la integración continua.

```bash
npx --yes ajv-cli@5.0.0 validate --spec=draft2020 -s resources/laravel-lang.schema.json -d 'src/*/laravel_lang.json'
```

Consulta [Complementos de traducción](./plugins.md) para conocer el formato del manifiesto y [Toolkit](./toolkit.md) para los comandos de mantenimiento del repositorio.
