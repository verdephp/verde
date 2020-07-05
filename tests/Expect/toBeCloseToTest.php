<?php

use function Verde\expect;
use function Verde\Test\fails;

it('passes when within two digits places by default', function () {
    expect(0.5 / 0.6)->toBeCloseTo(0.83);
});

it("fails when not within two digits places by default", function () {
    fails(function () {
        expect(0.5 / 0.7)->toBeCloseTo(0.7);
    });
});

it("accepts an optional digits argument", function () {
    expect(0.2 + 0.1)->toBeCloseTo(0.3, 1);
    expect(0.5 / 0.6)->toBeCloseTo(0.83, 2);

    fails(function () {
        expect(0.5 / 0.6)->not()->toBeCloseTo(0.83, 2);
    });
});
