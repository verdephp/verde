<?php

declare(strict_types=1);

use Verde\Test\DummyClass;
use function Verde\expect;
use function Verde\Test\fails;

it(
    'passes when size of received === expected',
    function () {
        $testArray = [1, 2, 3];

        expect($testArray)->toCount(3);
    }
);

it(
    'passes when the object implements Countable and size of received === expected',
    function () {
        expect(new DummyClass())->toCount(3);
    }
);

it(
    'fails when size of received !== expected',
    function () {
        $testArray = [1, 2, 3];

        fails(function () use ($testArray) {
            expect($testArray)->toCount(1);
        });
    }
);
