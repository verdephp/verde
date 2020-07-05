<?php

declare(strict_types=1);

use Verde\ANY;
use function Verde\expect;
use function Verde\Test\fails;

it(
    'passes when received === expected one',
    function () {
        expect(5)->toBe(5);
        expect('5')->toBe('5');
        expect([1, 2, 3])->toBe([1, 2, 3]);
        expect([1, [2, 3]])->toBe([1, [2, 3]]);
        expect([1, [2, [3, 4]]])->toBe([1, [2, [3, 4]]]);

        expect(function () {
            return 42;
        })->toBe(42);
    }
);

it(
    'passes when received is the same type of expected',
    function () {
        $test = function () {
            return 42;
        };
        $obj = new stdClass();
        $obj->students = array('Kalle', 'Ross', 'Felipe');

        expect(true)->toBe(ANY::BOOL);
        expect([1,2,3])->toBe(ANY::ARRAY);
        expect([1,2,3])->toBe(ANY::COUNTABLE);
        expect([1,2,3])->toBe(ANY::ITERABLE);
        expect(new ArrayIterator([1, 2, 3]))->toBe(ANY::ITERABLE);

        expect($test)->toBe(ANY::CALLABLE);

        expect(5)->toBe(ANY::INT);
        expect(doubleval(1.1321))->toBe(ANY::FLOAT);
        expect(floatval(1.1321))->toBe(ANY::FLOAT);
        expect(floatval(1.1321))->toBe(ANY::NUMERIC);

        expect(1.12)->not()->toBe(ANY::INFINITE);
        expect(log(0))->toBe(ANY::INFINITE);

        expect($obj)->toBe(ANY::OBJECT);

        expect("String")->toBe(ANY::STRING);
    }
);

it(
    'fails when received == expected one',
    function () {
        fails(
            function () {
                expect(5)->toBe('5');
            }
        );
        fails(
            function () {
                expect([1, 2])->toBe([2, 1]);
            }
        );
        fails(
            function () {
                expect([1, [2, 3]])->toBe([[2, 3], 1]);
            }
        );
    }
);
