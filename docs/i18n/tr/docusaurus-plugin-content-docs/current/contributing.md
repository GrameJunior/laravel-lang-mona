---
title: "Katkıda bulunma"
description: "Mona’daki çevirileri bulun, düzenleyin ve bir pull request açmadan önce değişiklikleri doğrulayın."
---

Değişiklikleri [Mona deposuna](https://github.com/Laravel-Lang/mona) gönderin. Mona, kaynak şablonları, ortak çevirileri ve PHP paketlerini tek bir depoda tutar.

## Çeviri düzeltme veya ekleme {#fix-or-add-a-translation}

1. Paketin `src/<Package>/source/` dizininde İngilizce ifadeyi bulun. Örneğin Laravel Framework ve Laravel’in resmî paketlerinin kaynakları `src/Lang/source/` altında yer alır.
2. `src/Library/locales/<locale>/default.json` dosyasını açın. İngilizce ifadeyi JSON anahtarı olarak bulun ve değerini düzenleyin. Kaynak ifade pakette bulunuyor ancak sözlükte bulunmuyorsa bir girdi ekleyin.
3. Kaynak ifadeyi değiştirmeyin ve `:count`, `:other`, `:attribute` gibi yer tutucuları koruyun. Satır içi doğrulama mesajlarında, alan adını anmayan bir ifadeyle aynı kaynak anahtarı `inline.json` dosyasında kullanın.
4. Çeviri özellikle İngilizce ifadeyle aynı bırakılmışsa ifadeyi yerel ayarın `_settings.json` dosyasındaki `excludes` listesine ekleyin. Toolkit çeviri durumunu hesaplamak için bu listeyi kullanır.
5. Başlığında yerel ayar kodunu içeren bir pull request açın. Örnek başlık `[fr]: Updated validation messages` olabilir.

Başka bir projenin kaynak ifadesinde yazım hatası bulursanız, kaynak metin ve arama anahtarı birlikte güncellenebilsin diye hatayı o projeye bildirin.

`src/Library/locales/fr/default.json` dosyasındaki varsayılan Fransızca girdiye bakın.

```json
{
  "The :attribute field is invalid.": "Le champ :attribute est invalide."
}
```

Aynı ifadenin satır içi karşılığı `src/Library/locales/fr/inline.json` dosyasında yer alır.

```json
{
  "The :attribute field is invalid.": "Ce champ est invalide."
}
```

Ülke, para birimi veya yerel ayar adlarını güncellemek için ilgili paketin `data/` dizinindeki sözlüğü düzenleyin. [Yerel ayarlar ve çevrilmiş adlar](./locales.md) sayfasına bakın.

## Yerel ayar önerme {#propose-a-locale}

[Standart yerel ayar kodlarına](./available-locales.md) bakın. Yeni bir kod önermek için [Laravel-Lang’de bir tartışma](https://github.com/orgs/Laravel-Lang/discussions/new?category=ideas&title=I%20want%20to%20add%20a%20new%20localization) açın. Toolkit, listede zaten bulunan kodlar için [dizin oluşturabilir](./toolkit.md#manage-locale-directories).

## Yerel kontrolleri çalıştırma {#run-local-checks}

Bağımlılıkları deponun kök dizininden kurun. Bir sözlük değişikliği yaptıysanız JSON’u doğrulayın ve çeviri durumunu kontrol edin. `status`, belirttiğiniz proje için rapor oluşturur.

```bash
composer install
php src/Toolkit/bin/toolkit status src/Library
```

PHP değişiklikleri için `composer test` ve `composer test:types` komutlarını çalıştırın. `composer style` betiği biçimlendirme ve kod stili düzeltmelerini uygular.

Manifest dosyasında değişiklik yaptıysanız CI ile aynı şema kontrolünü çalıştırın.

```bash
npx --yes ajv-cli@5.0.0 validate --spec=draft2020 -s resources/laravel-lang.schema.json -d 'src/*/laravel_lang.json'
```

Manifest biçimi için [çeviri eklentilerine](./plugins.md), depo bakım komutları için [Toolkit’e](./toolkit.md) bakın.
