<?php

use Verde\Expect;
use function Verde\expect;

require_once __DIR__ . '/TestUtils/Utils.php';

it(
    'expect returns a Expect class',
    function () {
        $expect = expect(null);

        assertTrue($expect instanceof Expect);
    }
);

it('executes the callable and uses its return value as expectation', function () {
    expect(function () {
        return 42;
    })->toBe(42);
});
