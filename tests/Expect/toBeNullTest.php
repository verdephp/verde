<?php

declare(strict_types=1);

use function Verde\expect;
use function Verde\Test\fails;

it(
    'passes when received === null',
    function () {
        expect(null)->toBeNull();
    }
);

it(
    'fails when received !== null',
    function () {
        fails(function () {
            expect("")->toBeNull();
        });
    }
);
