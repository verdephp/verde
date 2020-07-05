<?php

declare(strict_types=1);

use function Verde\expect;
use function Verde\Test\fails;

it(
    'passes when received == false',
    function () {
        expect(false)->toBeFalsy();
        expect(0)->toBeFalsy();
        expect('0')->toBeFalsy();
        expect(null)->toBeFalsy();
        expect([])->toBeFalsy();
        expect('')->toBeFalsy();
    }
);

it(
    'fails when received != false',
    function () {
        fails(
            function () {
                expect(1)->toBeFalse();
            }
        );
    }
);
