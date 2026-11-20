<?php

declare(strict_types=1);

function setCustomLocale(): void
{
    config()->set('app.locale', Workbench\App\Constants\FakeLocale::English);
}
