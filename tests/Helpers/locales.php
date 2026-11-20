<?php

declare(strict_types=1);

use Workbench\App\Constants\FakeLocale;

function setCustomLocale(): void
{
    config()->set('app.locale', FakeLocale::English);
}
