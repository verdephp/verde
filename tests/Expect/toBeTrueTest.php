<?php

declare(strict_types=1);

use function Verde\expect;
use function Verde\Test\fails;

it(
    'passes when received === true',
    function () {
        expect(true)->toBeTrue();
    }
);

it(
    'fails when received !== true',
    function () {
        fails(
            function () {
                expect(1)->toBeTrue();
            }
        );
    }
);
