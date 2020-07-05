<?php

declare(strict_types=1);

use function Verde\expect;
use function Verde\Test\fails;

it(
    'passes when received >= expected',
    function () {
        expect(5)->toBeGreaterThanOrEqual(4.9);
        expect(5)->toBeGreaterThanOrEqual(5);
    }
);

it(
    'fails when received <= expected ',
    function () {
        fails(
            function () {
                expect(4.9)->toBeGreaterThanOrEqual(5);
            }
        );
    }
);
