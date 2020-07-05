<?php

declare(strict_types=1);

use function Verde\expect;
use function Verde\Test\fails;

it(
    'passes when received is +INF',
    function () {
        expect(-log(0))->toBePositiveInfinity();
    }
);

it(
    'fails when received is not INF',
    function () {
        fails(function () {
            expect(2)->toBePositiveInfinity();
        });
    }
);

it(
    'fails when received is -INF',
    function () {
        fails(function () {
            expect(log(0))->toBePositiveInfinity();
        });
    }
);
