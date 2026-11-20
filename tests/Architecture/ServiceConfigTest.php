<?php

declare(strict_types=1);

arch('extending')
    ->expect('LaravelLang\Config\Services')
    ->toExtendNothing();

arch('suffix')
    ->expect('LaravelLang\Config\Services')
    ->toHaveSuffix('Config');
