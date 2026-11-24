---
title: مشارکت
description: ترجمه‌ها را در Mona پیدا و ویرایش کنید و پیش از باز کردن درخواست ادغام، تغییرات را بررسی کنید.
---

تغییرات را به [مخزن Mona](https://github.com/Laravel-Lang/mona) بفرستید. الگوهای منبع، ترجمه‌های مشترک و بسته‌های PHP همگی در یک مخزن Mona قرار دارند.

## اصلاح یا افزودن ترجمه {#fix-or-add-a-translation}

1. عبارت انگلیسی را در پوشهٔ `src/<Package>/source/` بسته پیدا کنید. برای نمونه، منابع Laravel Framework و بسته‌های رسمی Laravel در `src/Lang/source/` قرار دارند.
2. فایل `src/Library/locales/<locale>/default.json` را باز کنید. عبارت انگلیسی را به‌عنوان کلید JSON پیدا کنید و مقدارش را ویرایش کنید. اگر عبارت در منبع بسته هست و در واژه‌نامه وجود ندارد، مدخلی برای آن اضافه کنید.
3. عبارت منبع را دست‌نخورده نگه دارید و جای‌نگهدارهایی مانند `:count`، `:other` و `:attribute` را حفظ کنید. برای پیام اعتبارسنجی درون‌خطی، همان کلید را در `inline.json` با عبارتی به‌کار ببرید که نام فیلد در آن نیاید.
4. اگر ترجمه عمداً با عبارت انگلیسی یکسان است، آن عبارت را در بخش `excludes` فایل `_settings.json` همان زبان منطقه‌ای اضافه کنید. Toolkit در گزارش وضعیت ترجمه از این فهرست استفاده می‌کند.
5. درخواست ادغامی باز کنید که کد زبان منطقه‌ای در عنوانش باشد، مانند `[fr]: Updated validation messages`.

اگر غلط املایی در عبارت منبعِ یک پروژهٔ بالادستی پیدا کردید، آن را به همان پروژه گزارش کنید تا عبارت منبع و کلید جست‌وجوی آن با هم به‌روز شوند.

برای نمونه، مدخل پیش‌فرض فرانسوی در `src/Library/locales/fr/default.json` به این صورت است.

```json
{
  "The :attribute field is invalid.": "Le champ :attribute est invalide."
}
```

مدخل درون‌خطی متناظر در `src/Library/locales/fr/inline.json` قرار دارد.

```json
{
  "The :attribute field is invalid.": "Ce champ est invalide."
}
```

برای به‌روزرسانی نام کشورها، ارزها یا زبان‌های منطقه‌ای، واژه‌نامهٔ پوشهٔ `data/` بستهٔ مربوط را ویرایش کنید. بخش [زبان‌های منطقه‌ای و نام‌های ترجمه‌شده](./locales.md) را ببینید.

## پیشنهاد زبان منطقه‌ای {#propose-a-locale}

[کدهای استاندارد زبان‌های منطقه‌ای](./available-locales.md) را بررسی کنید. برای پیشنهاد کد جدید، یک [بحث در Laravel-Lang](https://github.com/orgs/Laravel-Lang/discussions/new?category=ideas&title=I%20want%20to%20add%20a%20new%20localization) باز کنید. Toolkit می‌تواند برای کدهای موجود در فهرست [پوشه بسازد](./toolkit.md#manage-locale-directories).

## اجرای بررسی‌های محلی {#run-local-checks}

از ریشهٔ مخزن وابستگی‌ها را نصب کنید. برای تغییر واژه‌نامه، اعتبار JSON و وضعیت ترجمه را بررسی کنید. دستور `status` گزارش‌های پروژه‌ای را می‌نویسد که به آن می‌دهید.

```bash
composer install
php src/Toolkit/bin/toolkit status src/Library
```

برای تغییرات PHP دستورهای `composer test` و `composer test:types` را اجرا کنید. اسکریپت `composer style` قالب‌بندی و اصلاحات سبک کد را اعمال می‌کند.

برای تغییرات مانیفست، همان بررسی شِما را اجرا کنید که در CI استفاده می‌شود.

```bash
npx --yes ajv-cli@5.0.0 validate --spec=draft2020 -s resources/laravel-lang.schema.json -d 'src/*/laravel_lang.json'
```

برای فرمت مانیفست به [افزونه‌های ترجمه](./plugins.md) و برای فرمان‌های نگه‌داری مخزن به [Toolkit](./toolkit.md) مراجعه کنید.
