<?php
declare(strict_types=1);

use PHPUnit\Framework\ExpectationFailedException;

use function Verde\Test\fails;

it(
    'throws and error if the assert fails',
    function () {
        try {
            fails(function () {
                assertFalse(false);
            });
        } catch (ExpectationFailedException $e) {
            assertTrue(true);

            return;
        }

        assertFalse(true);
    }
);

it(
    'does not throw an error if the assert passes',
    function () {
        try {
            fails(function () {
                assertTrue(false);
            });
        } catch (ExpectationFailedException $e) {
            assertFalse(true);

            return;
        }

        assertTrue(true);
    }
);
