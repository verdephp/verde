<?php

use Verde\Test\DummyClass;

use function Verde\expect;
use function Verde\Test\fails;

require_once __DIR__ . "/TestUtils/Utils.php";

// describe('toBe');
it(
    "pass when received !== expected",
    function () {
        expect(5)->not()->toBe("5");
    }
);

it(
    "fails when received === expected",
    function () {
        define('EHE', 1);
        fails(
            function () {
                expect(5)->not()->toBe(5);
            }
        );
    }
);

// describe('toBeFalse');
it(
    "fails when received === false",
    function () {
        fails(
            function () {
                expect(false)->not()->toBeFalse();
            }
        );
    }
);

it(
    "pass when received !== false",
    function () {
        expect(0)->not()->toBeFalse();
    }
);

// describe('toBeFalsy');
it(
    "fails when received == false",
    function () {
        fails(
            function () {
                expect(false)->not()->toBeFalsy();
            }
        );
        fails(
            function () {
                expect(0)->not()->toBeFalsy();
            }
        );
        fails(
            function () {
                expect("0")->not()->toBeFalsy();
            }
        );
        fails(
            function () {
                expect(null)->not()->toBeFalsy();
            }
        );
        fails(
            function () {
                expect([])->not()->toBeFalsy();
            }
        );
        fails(
            function () {
                expect("")->not()->toBeFalsy();
            }
        );
    }
);

it(
    "pass when received != false",
    function () {
        expect(1)->not()->toBeFalse();
    }
);

// describe('toBeGreaterThan');
it(
    "fails when received > expected",
    function () {
        $error = null;

        fails(
            function () {
                expect(5)->not()->toBeGreaterThan(4.9);
            }
        );
    }
);

it(
    "pass when received < expected",
    function () {
        expect(1)->not()->toBeGreaterThan(5);
        expect(5)->not()->toBeGreaterThan(5);
    }
);

// describe('toBeGreaterThanOrEqual');
it(
    "fails when received >= expected",
    function () {
        $error = null;

        fails(
            function () {
                expect(5)->not()->toBeGreaterThanOrEqual(4.9);
            }
        );

        fails(
            function () {
                expect(5)->not()->toBeGreaterThanOrEqual(5);
            }
        );
    }
);

it(
    "pass when received <= expected",
    function () {
        expect(1)->not()->toBeGreaterThanOrEqual(5);
    }
);


// describe('toBeTrue');
it(
    "fails when received === true",
    function () {
        fails(
            function () {
                expect(true)->not()->toBeTrue();
            }
        );
    }
);

it(
    "pass when received !== true",
    function () {
        expect(false)->not()->toBeTrue();
    }
);

// describe('toCount');
it(
    "fails when size of array === count",
    function () {
        fails(function () {
            $testArray = [1, 2, 3];

            expect($testArray)->not()->toCount(3);
        });
    }
);

it(
    "fails when the object implements Countable and size of array === count",
    function () {
        fails(function () {
            expect(new DummyClass())->not()->toCount(3);
        });
    }
);

it(
    "pass when size of array !== count",
    function () {
        $testArray = [1, 2, 3];

        expect($testArray)->not()->toCount(1);
    }
);

// describe('toHaveMethod');
it(
    "pass if the class does not have the specified method",
    function () {
        $dummyClass = new DummyClass();

        expect($dummyClass)->not()->toHaveMethod('doWhatever');
        expect(DummyClass::class)->not()->toHaveMethod('doTest');
    }
);

it(
    "fails if the class does  have the specified method",
    function () {
        fails(
            function () {
                $dummyClass = new DummyClass();
                expect($dummyClass)->not()->toHaveMethod('doSomething');
            }
        );

        fails(
            function () {
                expect(DummyClass::class)->not()->toHaveMethod('doSomething');
            }
        );
    }
);

// describe('toThrow')
it(
    'passes when the expected callback does not throw an error',
    function () {
        expect(function () {
            return 42;
        })->not()->toThrow();
    }
);
