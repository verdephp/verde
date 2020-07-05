<?php

declare(strict_types=1);

use function Verde\expect;
use function Verde\Test\fails;

it(
    'passes when received is a finite number',
    function () {
        expect(42)->toBeFinite();
    }
);

it(
    'fails when received is not a finite number',
    function () {
        fails(function () {
            $infinite = log(0);

            expect($infinite)->toBeFinite();
        });

        fails(function () {
            $nan = acos(2);

            expect($nan)->toBeFinite();
        });
    }
);
