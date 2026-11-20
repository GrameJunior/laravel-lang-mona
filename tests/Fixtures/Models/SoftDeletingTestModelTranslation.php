<?php

declare(strict_types=1);

namespace Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\SoftDeletes;
use Workbench\App\Models\TestModelTranslation;

#[Table(name: 'test_model_translations')]
class SoftDeletingTestModelTranslation extends TestModelTranslation
{
    use SoftDeletes;
}
