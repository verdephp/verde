<?php

declare(strict_types=1);

use function Verde\expect;
use function Verde\Test\fails;

it(
    'passes when received === false',
    function () {
        expect(false)->toBeFalse();
    }
);

it(
    'fails when received !== false',
    function () {
        fails(
            function () {
                expect(0)->toBeFalse();
            }
        );
    }
);
