<?php
declare(strict_types=1);

use Verde\Mock;
use Verde\Test\AnotherDummy;
use function Verde\expect;
use function Verde\mock;

it('seturn the instance of the original mocked class', function () {
    $test = mock(AnotherDummy::class);

    expect($test)->toBeInstanceOf(Mock::class);
    expect($test->getMock())->toBeInstanceOf(AnotherDummy::class);

    // the class must be manually un-mocked after the test!
    $test->mockRestore();
});

it('mocks all the class methods when the custom mocks are not specified', function () {
    $test = mock(AnotherDummy::class);
    $result = $test->getMock()->test();

    expect($result)->not()->toBe(123);
    expect($result)->toBe(null);

    $test->mockRestore();
});

it('uses the custom mocked method when specified', function () {
    $test = mock(AnotherDummy::class, [
        'test' => \Verde\func(function () {
            return 'it works';
        }),
    ]);
    $mock = $test->getMock();

    expect($mock->test())->toBe('it works');

    // Methods not specified are still mocked!
    expect($mock->doSomething())->toBe(null);

    $test->mockRestore();
});

it('restores the original implementation', function () {
    $test = mock(AnotherDummy::class, [
        'test' => \Verde\func(function () {
            return 'it works';
        }),
    ]);
    $mock = $test->getMock();

    expect($mock->test())->toBe('it works');

    $test->mockRestore();

    // Methods not specified are still mocked!
    expect($mock->test())->toBe(123);
});
