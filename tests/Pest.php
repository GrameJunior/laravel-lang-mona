<?php

use Tests\TestCase;

pest()
    ->printer()
    ->compact();

pest()
    ->extend(TestCase::class)
    ->in('Feature')
    ->beforeEach(function () {
        cleanUpLang();
    });
