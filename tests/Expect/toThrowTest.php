<?php

declare(strict_types=1);

use Verde\Test\AnotherDummy;

use function Verde\expect;
use function Verde\Test\fails;

it(
    'passes when the received callback throws an error',
    function () {
        expect(function () {
            throw new Exception('Something went wrong');
        })->toThrow();

        expect(function () {
            $test = new AnotherDummy();

            $test->throwError();
        })->toThrow('Please try again');
    }
);

it(
    'fails when the received error message does not matches the expected one',
    function () {
        fails(function () {
            expect(function () {
                $test = new AnotherDummy();

                $test->throwError();
            })->toThrow('Fails when the error does not match the expectation');
        });

        fails(function () {
            expect(function () {
                throw new Error("This must fail!");
            })->toThrow('Fails when the error does not match the expectation');
        });
    }
);
