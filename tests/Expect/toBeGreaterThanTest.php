<?php

declare(strict_types=1);

use function Verde\expect;
use function Verde\Test\fails;

it(
    'passes when received > expected',
    function () {
        expect(5)->toBeGreaterThan(4.9);
    }
);

it(
    'fails when received <= expected',
    function () {
        $error = null;

        fails(
            function () {
                expect(5)->toBeGreaterThan(5);
            }
        );
        fails(
            function () {
                expect(1)->toBeGreaterThan(5);
            }
        );
    }
);
