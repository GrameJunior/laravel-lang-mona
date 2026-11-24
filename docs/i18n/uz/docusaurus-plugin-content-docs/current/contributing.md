---
title: "Hissa qo‘shish"
description: "Mona’da tarjimalarni toping, tahrirlang va o‘zgarishlarni pull request ochishdan oldin tekshiring."
---

O‘zgarishlarni [Mona repozitoriysiga](https://github.com/Laravel-Lang/mona) yuboring. Mona manba andozalari, umumiy tarjimalar hamda PHP paketlarini bitta repozitoriyda saqlaydi.

## Tarjimani tuzatish yoki qo‘shish {#fix-or-add-a-translation}

1. Inglizcha iborani paketning `src/<Package>/source/` katalogidan toping. Masalan, Laravel Framework va rasmiy Laravel paketlarining manbalari `src/Lang/source/` katalogida.
2. `src/Library/locales/<locale>/default.json` faylini oching. Inglizcha iborani JSON kaliti sifatida topib, qiymatini tahrirlang. Manba ibora paketda bor-u, lug‘atda bo‘lmasa, yozuv qo‘shing.
3. Manba iborani o‘zgartirmang va `:count`, `:other` hamda `:attribute` kabi o‘rinbosarlarni saqlang. Maydon yonida ko‘rsatiladigan tekshiruv xabari uchun `inline.json` faylida o‘sha manba kalitidan foydalaning va maydon nomini tilga olmagan holda ifodalang.
4. Agar tarjima ataylab inglizcha ibora bilan bir xil bo‘lsa, uni lokalning `_settings.json` faylidagi `excludes` ro‘yxatiga kiriting. Toolkit tarjima holatini shakllantirganda bu ro‘yxatdan foydalanadi.
5. Sarlavhasida lokal kodi bo‘lgan pull request oching, masalan, `[fr]: Updated validation messages`.

Yuqori oqim loyihasining manba iborasida imlo xatosini topsangiz, manba ibora va unga mos qidiruv kaliti birga yangilanishi uchun o‘sha loyihaga xabar bering.

Masalan, `src/Library/locales/fr/default.json` faylidagi fransuzcha standart yozuv quyidagicha:

```json
{
  "The :attribute field is invalid.": "Le champ :attribute est invalide."
}
```

`src/Library/locales/fr/inline.json` faylidagi unga mos yozuv:

```json
{
  "The :attribute field is invalid.": "Ce champ est invalide."
}
```

Mamlakat, valyuta yoki lokal nomlarini yangilash uchun tegishli paketning `data/` katalogidagi lug‘atni tahrirlang. [Lokallar va tarjima qilingan nomlar](./locales.md) bo‘limiga qarang.

## Lokal taklif qilish {#propose-a-locale}

[Kanonik lokal kodlarini](./available-locales.md) tekshiring. Yangi kodni taklif qilish uchun [Laravel-Lang muhokamasini](https://github.com/orgs/Laravel-Lang/discussions/new?category=ideas&title=I%20want%20to%20add%20a%20new%20localization) oching. Toolkit ro‘yxatda bor kodlar uchun [kataloglar yaratishi](./toolkit.md#manage-locale-directories) mumkin.

## Mahalliy tekshiruvlarni bajarish {#run-local-checks}

Bog‘liqliklarni repozitoriy ildizidan o‘rnating. Lug‘at o‘zgarganda JSON faylingizning yaroqliligini va tarjima holatini tekshiring. `status` siz ko‘rsatgan loyiha uchun hisobotlar yozadi.

```bash
composer install
php src/Toolkit/bin/toolkit status src/Library
```

PHP o‘zgarishlari uchun `composer test` va `composer test:types` buyruqlarini bajaring. `composer style` skripti formatlash va kod uslubiga oid tuzatishlarni qo‘llaydi.

Manifestdagi o‘zgarishlar uchun CI’da ishlatiladigan sxema tekshiruvini bajaring.

```bash
npx --yes ajv-cli@5.0.0 validate --spec=draft2020 -s resources/laravel-lang.schema.json -d 'src/*/laravel_lang.json'
```

Manifest formati uchun [tarjima plaginlari](./plugins.md), repozitoriyga xizmat ko‘rsatish buyruqlari uchun esa [Toolkit](./toolkit.md) bo‘limiga qarang.
