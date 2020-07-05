<?php

declare(strict_types=1);

use function Verde\expect;
use function Verde\Test\fails;

it(
    'passes when received == true',
    function () {
        expect(true)->toBeTruthy();
        expect(1)->toBeTruthy();
        expect(-1)->toBeTruthy();
        expect("-1")->toBeTruthy();
    }
);

it(
    'fails when received != true',
    function () {
        fails(function () {
            expect(0)->toBeTruthy();
        });
    }
);
