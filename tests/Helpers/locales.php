<?php

declare(strict_types=1);

function setCustomLocale(): void
{
    config()->set('app.locale', 'en');
}
