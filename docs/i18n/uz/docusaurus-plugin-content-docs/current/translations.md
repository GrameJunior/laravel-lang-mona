---
title: "Tarjimalarni boshqarish"
description: "Mona Publisher yordamida ilova va bog‘liqlik tarjimalarini qo‘shing, yangilang va olib tashlang."
---

Publisher rasmiy tarjima paketlari tarkibiga kiradi. Uning Artisan buyruqlari PHP va JSON tarjimalarini ilovaning til katalogiga, odatda `lang/` ga yozadi. Ayrim paket fayllari `lang/vendor/` ostiga ham yozilishi mumkin.

## Lokallar qo‘shish {#add-locales}

Bitta yoki bir nechta [lokal kodini](./available-locales.md) bo‘sh joy bilan ajratib uzating. Sozlangan muqobil nomlar kirishda qabul qilinadi, hosil bo‘lgan fayllarda esa kanonik kodlar ishlatiladi.

```bash
php artisan lang:add fr de_CH
```

Buyruq lokal kataloglarini yaratadi, so‘ng **barcha o‘rnatilgan lokallarni**, jumladan ilovaning joriy va zaxira lokallarini yangilaydi. Argumentlarsiz u barcha qo‘llab-quvvatlanadigan lokallarni o‘rnatish kerakligini so‘raydi. Avtomatlashtirishda kodlarni aniq uzating.

## Tarjimalarni yangilash {#update-translations}

```bash
php artisan lang:update
```

Publisher lokallarni ilovaning joriy va zaxira lokali, til katalogi ildizidagi fayl yoki kataloglar hamda `lang/vendor/<package>/` ostidagi JSON fayllaridan aniqlaydi. Bog‘liqlikning PHP tarjima katalogi o‘zi lokalni o‘rnatilgan deb belgilash uchun yetarli emas. Ilova katalogini yaratish uchun o‘sha lokalga `lang:add` buyrug‘ini qo‘llang.

Tarjima paketlari qo‘llab-quvvatlanadigan paket va versiyalarni, shuningdek maqsad fayllarini [plagin manifestlarida](./plugins.md) e’lon qiladi. Publisher umumiy va paket lug‘atlarini birlashtiradi, so‘ng tarjimalarni har bir maqsad fayliga qo‘shadi. U aloqasiz kalitlarni saqlaydi va mos satr kalitlari qiymatini almashtiradi. Lug‘atlarda ibora topilmasa, Publisher manba iborani yozadi. O‘zingizga xos almashtirishlarni alohida tarjima fayllarida saqlang yoki mustaqil yuritadigan maqsad fayllarini [nashrdan chiqarib tashlang](./configure.md#skip-files-and-force-integrations).

Composer bog‘liqliklari yoki [nashr sozlamalari](./configure.md) o‘zgargach, `lang:update` buyrug‘ini bajaring. Hosil bo‘lgan farqlarni ko‘rib chiqing. Birlashtirish sonli kalitlarni qayta raqamlashi mumkin. Ilovangiz yaratilgan `http-statuses.php` fayllaridan foydalansa, ulardagi holat kodlarini tekshiring.

## Lokallarni olib tashlash {#remove-locales}

```bash
php artisan lang:rm de_CH
```

Buyruq kanonik lokal katalogi va JSON faylini til katalogi ildizidan hamda har bir bevosita `lang/vendor/<package>/` katalogidan o‘chiradi. O‘chiriladigan lokal kataloglari ichidagi maxsus fayllar ham o‘chadi. Joriy va zaxira lokallar himoyalangan. Argumentlarsiz buyruq barcha himoyalanmagan lokallarni olib tashlash kerakligini so‘raydi. Standart javob — yo‘q.

Buyruq har bir lokal uchun `DONE`, `PROTECTED`, `INVALID` yoki `SKIP` holatini bildiradi. Bu holatlarning har biri muvaffaqiyatli chiqish kodi bilan kelishi mumkin. Shuning uchun o‘chirilganini tasdiqlashi kerak bo‘lgan avtomatlashtirishda har bir so‘ralgan lokal natijasini tekshirish tavsiya etiladi.

## Zaxira JSON tarjimalari {#json-fallback}

`laravel-lang/json-fallback` JSON kaliti topilmaganda PHP tarjimalariga o‘tishdan oldin zaxira lokalning JSON faylidan qidirishni qo‘shadi. Paket Common tarkibiga kiradi. Alohida o‘rnatish uchun `composer require laravel-lang/json-fallback` buyrug‘ini bajaring.

`LaravelLang\JsonFallback\LangJsonFallbackServiceProvider::class` ni `bootstrap/providers.php` faylidagi ilova provayderlariga qo‘shing. Ilovada `Illuminate\Translation\TranslationServiceProvider` aniq ko‘rsatilgan bo‘lsa, ushbu yozuvni Mona provayderiga almashtiring. [Laravel provayderlarini ro‘yxatdan o‘tkazish](https://laravel.com/docs/13.x/providers#registering-providers)

Joriy lokal fransuz tili, zaxira lokal esa ingliz tili bo‘lsa, `lang/fr.json` da yo‘q kalit uchun endi `lang/en.json` dagi qiymat qaytishi mumkin.
