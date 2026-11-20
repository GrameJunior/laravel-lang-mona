<?php

declare(strict_types=1);

namespace Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Workbench\App\Models\TestModel;

#[Table(name: 'test_models')]
class SoftDeletingTestModel extends TestModel
{
}
