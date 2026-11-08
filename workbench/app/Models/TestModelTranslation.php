<?php

declare(strict_types=1);

namespace Workbench\App\Models;

use LaravelLang\Models\Casts\TrimCast;
use LaravelLang\Models\Eloquent\Translation;

class TestModelTranslation extends Translation
{
    protected $fillable = [
        'locale',
        'title',
        'description',
    ];

    protected $casts = [
        'title'       => TrimCast::class,
        'description' => TrimCast::class,
    ];
}
