<?php

declare(strict_types=1);

use Verde\Test\DummyClass;

use function Verde\expect;
use function Verde\Test\fails;

it(
    'passes when the received class has the expected method',
    function () {
        $dummyClass = new DummyClass();

        expect($dummyClass)->toHaveMethod('doSomething');
        expect(DummyClass::class)->toHaveMethod('doSomething');
    }
);

it(
    'fails when the received class does not have the expected method',
    function () {
        fails(
            function () {
                $dummyClass = new DummyClass();
                expect($dummyClass)->toHaveMethod('doWhatever');
            }
        );

        fails(
            function () {
                expect(DummyClass::class)->toHaveMethod('doTry');
            }
        );
    }
);
