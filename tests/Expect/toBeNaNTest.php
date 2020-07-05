<?php

declare(strict_types=1);

use function Verde\expect;
use function Verde\Test\fails;

it(
    'passes when received is nan',
    function () {
        expect(asin(2))->toBeNaN();
    }
);

it(
    'fails when received is a number',
    function () {
        fails(function () {
            expect(2)->toBeNaN();
        });
    }
);

it(
    'fails when received is infinite',
    function () {
        fails(function () {
            expect(log(0))->toBeNaN();
        });
    }
);
