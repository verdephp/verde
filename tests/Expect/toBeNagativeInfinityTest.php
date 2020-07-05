<?php

declare(strict_types=1);

use function Verde\expect;
use function Verde\Test\fails;

it(
    'passes when received is -INF',
    function () {
        expect(log(0))->toBeNegativeInfinity();
    }
);

it(
    'fails when received is a number',
    function () {
        fails(function () {
            expect(2)->toBeNegativeInfinity();
        });
    }
);

it(
    'fails when received is +INF',
    function () {
        fails(function () {
            expect(-log(0))->toBeNegativeInfinity();
        });
    }
);
