<?php

use function Verde\expect;
use function Verde\Test\fails;

it(
    'passes when the string length matches the expected one',
    function () {
        expect("Ciao")->toHaveLength(4);

        fails(function () {
            expect("Ciao")->not()->toHaveLength(4);
        });
    }
);

it(
    'fails when the string length does not matche the expected one',
    function () {
        fails(function () {
            expect("Ciao")->toHaveLength(5);
        });
    }
);

it(
    'passes when the array size matches the expected one',
    function () {
        expect([1,2, 3])->toHaveLength(3);

        fails(function () {
            expect([1,2,3])->not()->toHaveLength(3);
        });
    }
);

it(
    'fails when the array size does not match the expected one',
    function () {
        fails(function () {
            expect([1,2, 3])->toHaveLength(2);
        });
    }
);
