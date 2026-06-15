<?php

declare(strict_types=1);

use LaravelLang\Config\Data\ConfigData;
use LaravelLang\Config\Enums\Name;

function setConfig(Name $root, string $key, mixed $value): void
{
    config()?->set($root->value . '.' . $key, $value);
}

function getConfig(): ConfigData
{
    return app(ConfigData::class);
}
