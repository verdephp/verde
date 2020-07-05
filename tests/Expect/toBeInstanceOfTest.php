<?php

declare(strict_types=1);

use Verde\Test\AnotherDummy;
use Verde\Test\DummyClass;

use function Verde\expect;
use function Verde\Test\fails;

it(
    'passes when received is an instance of expected',
    function () {
        $a = new DummyClass();

        expect($a)->toBeInstanceOf(DummyClass::class);
    }
);

it(
    'passes when received extends the expected class',
    function () {
        $b = new AnotherDummy();

        expect($b)->toBeInstanceOf(DummyClass::class);
        expect($b)->toBeInstanceOf(AnotherDummy::class);
    }
);

it(
    'fails if received is not instance of expected',
    function () {
        fails(
            function () {
                $a = new Directory();

                expect($a)->toBeInstanceOf(DummyClass::class);
            }
        );
    }
);
