<?php

declare(strict_types=1);

use Verde\Test\AnotherDummy;
use Verde\Test\MyException;
use function Verde\expect;
use function Verde\Test\fails;

it(
    'passes if the error class matches the expected one',
    function () {
        expect(function () {
            $test = new AnotherDummy();

            $test->throwError();
        })->toThrowError(MyException::class);
    }
);

it(
    'passes if the error class and message matches the expected one',
    function () {
        expect(function () {
            $test = new AnotherDummy();

            $test->throwError();
        })->toThrowError(MyException::class, 'Please try again');
    }
);

it(
    'fails if the error class does not match the expected one',
    function () {
        fails(function () {
            expect(function () {
                $test = new AnotherDummy();

                $test->throwError();
            })->toThrowError(Exception::class);
        });
    }
);

it(
    'fails if the message does not match the expected one',
    function () {
        fails(function () {
            expect(function () {
                $test = new AnotherDummy();

                $test->throwError();
            })->toThrowError(MyException::class, "Error does not matches");
        });
    }
);
