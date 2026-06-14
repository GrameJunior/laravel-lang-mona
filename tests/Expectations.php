<?php

declare(strict_types=1);

expect()->pipe('toMatchSnapshot', function (Closure $next) {
    $flags = JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT;

    $this->value = json_encode($this->value, $flags);

    return $next();
});
