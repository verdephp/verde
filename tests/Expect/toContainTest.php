<?php

declare(strict_types=1);

use function Verde\expect;
use function Verde\Test\fails;

it(
    'passes when received contains the substring',
    function () {
        expect("ciao")->toContain("ci");
        expect("hello world")->toContain("ld");
        expect("hello world")->toContain("o w");
    }
);

it(
    'passes when the array contains the value',
    function () {
        expect(['hello', 'world'])->toContain('hello');
    }
);

it(
    'passes when the array contains the expected subset',
    function () {
        expect(['hello', 'world', 'ciao', 'today'])->toContain(['world', 'ciao']);
        expect(['hello', 'world', 'ciao', 'today'])->toContain(['ciao', 'hello']);

        fails(function () {
            expect(['hello', 'world', 'ciao', 'today'])->toContain(['ciao', 'bello']);
        });
    }
);

it(
    'fails when received does not contain the substring',
    function () {
        fails(function () {
            expect("ciao")->toContain("o.");
            expect("hello, world")->toContain("o w");
        });
    }
);

it(
    'fails when the array does not contain the value',
    function () {
        fails(function () {
            expect(['hello', 'world'])->toContain('ciao');
        });
    }
);
