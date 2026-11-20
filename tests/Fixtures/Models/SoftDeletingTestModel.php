<?php

declare(strict_types=1);

namespace Tests\Fixtures\Models;

use Workbench\App\Models\TestModel;

class SoftDeletingTestModel extends TestModel
{
    protected $table = 'test_models';
}
