<?php

declare(strict_types=1);

use function Verde\expect;
use function Verde\Test\fails;

it(
    'passes when received <= expected',
    function () {
        expect(42)->toBeLessThanOrEqual(42);
        expect(42)->toBeLessThanOrEqual(42.5);
    }
);

it(
    'fails when received > expected',
    function () {
        fails(function () {
            expect(42.1)->toBeLessThanOrEqual(42);
        });
    }
);
