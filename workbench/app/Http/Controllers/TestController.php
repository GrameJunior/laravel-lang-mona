<?php

declare(strict_types=1);

namespace Workbench\App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Workbench\App\Constants\FakeTranslation;

use function __;
use function response;

class TestController
{
    public function __invoke(string $key): JsonResponse
    {
        return response()->json([
            $key => __(FakeTranslation::Key),
        ]);
    }
}
