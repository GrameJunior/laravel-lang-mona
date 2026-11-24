---
title: "المساهمة"
description: "اعثر على الترجمات في Mona وحرّرها، ثم تحقّق من التغييرات قبل فتح طلب سحب."
---

أرسل التغييرات إلى [مستودع Mona](https://github.com/Laravel-Lang/mona). يجمع Mona القوالب المصدرية والترجمات المشتركة وحزم PHP في مستودع واحد.

## تصحيح ترجمة أو إضافتها {#fix-or-add-a-translation}

1. ابحث عن العبارة الإنجليزية في مجلد `src/<Package>/source/` الخاص بالحزمة. مثلًا، توجد مصادر إطار Laravel وحزمه الرسمية في `src/Lang/source/`.
2. افتح `src/Library/locales/<locale>/default.json`. اعثر على العبارة الإنجليزية بوصفها مفتاح JSON وحرّر قيمته. أضف مدخلًا إذا كانت العبارة المصدرية موجودة في الحزمة وغائبة عن القاموس.
3. أبقِ العبارة المصدرية كما هي وحافظ على العناصر النائبة مثل `:count` و`:other` و`:attribute`. لرسالة تحقق لا تذكر اسم الحقل، استخدم المفتاح المصدري نفسه في `inline.json` مع صياغة تتجنّب تسمية الحقل.
4. إذا كانت الترجمة مطابقة عمدًا للعبارة الإنجليزية، أضف العبارة إلى ملف `_settings.json` للإعداد المحلي ضمن `excludes`. تستخدم Toolkit هذه القائمة عند توليد تقارير حالة الترجمة.
5. افتح طلب سحب يتضمن عنوانه رمز الإعداد المحلي، مثل `[fr]: Updated validation messages`.

إذا وجدت خطأً إملائيًا في عبارة مصدرية تابعة لمشروع أصلي، أبلغ عنه هناك ليُحدّث المصدر ومفتاح البحث معًا.

مثلًا، المدخل الافتراضي للفرنسية في `src/Library/locales/fr/default.json` هو:

```json
{
  "The :attribute field is invalid.": "Le champ :attribute est invalide."
}
```

والمدخل المقابل الذي لا يذكر اسم الحقل في `src/Library/locales/fr/inline.json` هو:

```json
{
  "The :attribute field is invalid.": "Ce champ est invalide."
}
```

لتحديث أسماء البلدان أو العملات أو الإعدادات المحلية، حرّر القاموس في مجلد `data/` الخاص بالحزمة المعنية. راجع [الإعدادات المحلية والأسماء المترجمة](./locales.md).

## اقتراح إعداد محلي {#propose-a-locale}

راجع [الرموز المعتمدة للإعدادات المحلية](./available-locales.md). ولاقتراح رمز جديد، افتح [نقاشًا في Laravel-Lang](https://github.com/orgs/Laravel-Lang/discussions/new?category=ideas&title=I%20want%20to%20add%20a%20new%20localization). تستطيع Toolkit [إنشاء المجلدات](./toolkit.md#manage-locale-directories) للرموز الموجودة في القائمة بالفعل.

## تشغيل الفحوصات محليًا {#run-local-checks}

ثبّت التبعيات من جذر المستودع. إذا غيّرت قاموسًا، فتحقّق من صحة JSON وحالة الترجمة. يكتب `status` تقارير للمشروع الذي تمرّره إليه.

```bash
composer install
php src/Toolkit/bin/toolkit status src/Library
```

عند إجراء تغييرات في PHP، شغّل `composer test` و`composer test:types`. ويطبّق البرنامج النصي `composer style` إصلاحات التنسيق وأسلوب الشيفرة.

عند تعديل ملفات التعريف، شغّل فحص المخطط نفسه المستخدم في التكامل المستمر.

```bash
npx --yes ajv-cli@5.0.0 validate --spec=draft2020 -s resources/laravel-lang.schema.json -d 'src/*/laravel_lang.json'
```

راجع [إضافات الترجمة](./plugins.md) لصيغة ملف التعريف و[Toolkit](./toolkit.md) لأوامر صيانة المستودع.
