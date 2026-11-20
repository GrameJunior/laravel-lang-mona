<?php

declare(strict_types=1);

namespace Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Workbench\App\Models\TestModelTranslation;

class SoftDeletingTestModelTranslation extends TestModelTranslation
{
    use SoftDeletes;

    protected $table = 'test_model_translations';
}
