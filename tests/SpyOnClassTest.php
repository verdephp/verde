<?php

use Verde\Test\AnotherDummy;
use Verde\Test\DummyClass;
use function Verde\expect;
use function Verde\spyOn;

it('spies on the class method', function () {
    $spy = spyOn(DummyClass::class, 'doSomething');

    $test = new DummyClass();
    $test->doSomething();

    expect($spy)->toHaveBeenCalledTimes(1);
    expect($spy)->toHaveBeenCalledWith();

    $spy->mockRestore();
});

it('spies on private method', function () {
    $spy = spyOn(DummyClass::class, 'doIT');

    $test = new AnotherDummy();
    $test->test();

    expect($spy)->toHaveBeenCalledWith();

    $spy->mockRestore();
});

it('mocks the method', function () {
    $spy = spyOn(DummyClass::class, 'doSomething');
    $spy->mockImplementation(function () {
        return 'it works!';
    });

    $test = new DummyClass();
    $result = $test->doSomething();

    expect($result)->toBe('it works!');
    $spy->mockRestore();
});

it('clear the mock', function () {
    $spy = spyOn(DummyClass::class, 'doSomething');
    $spy->mockImplementation(function () {
        return 'it works!';
    });

    $test = new DummyClass();
    $result = $test->doSomething();

    expect($result)->toBe('it works!');

    $spy->mockRestore();

    $result = $test->doSomething();
    expect($result)->toBe(42);

    $spy->mockRestore();
});

it('mocks the method once', function () {
    $spy = spyOn(DummyClass::class, 'doSomething');
    $spy->mockRestore();

    $spy->mockImplementationOnce(function () {
        return 'it works!';
    });

    $test = new DummyClass();
    $result = $test->doSomething();

    expect($result)->toBe('it works!');

    $result = $test->doSomething();
    expect($result)->toBe(42);
});
