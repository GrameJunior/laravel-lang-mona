<?php

declare(strict_types=1);

arch('naming')
    ->expect('LaravelLang\Publisher\Collectors')
    ->toHaveSuffix('Collector');
