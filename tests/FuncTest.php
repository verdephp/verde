<?php
declare(strict_types=1);

use Verde\ANY;
use Verde\Func;
use function Verde\expect;
use function Verde\func;
use function Verde\Test\fails;

it('returns an instance of Func', function () {
    $func = func();

    expect($func)->toBeInstanceOf(Func::class);
});


it('returns a unique Func instance', function () {
    $func1 = func();
    $func2 = func();

    expect($func1)->not()->toBe($func2);
});

// getCallable
it('getCallable: returns a callable function', function () {
    $func = func();

    expect($func)->toHaveMethod('getCallable');
    expect(is_callable($func->getCallable()))->toBeTrue();
});

// toHaveBeenCalled
it('toHaveBeenCalled: records the calls', function () {
    $func = func();

    $func->getCallable()();

    expect($func)->toHaveBeenCalled();
});

// toHaveBeenCalledWith
it('toHaveBeenCalledWith: records the called arguments', function () {
    $func = func();

    $func->getCallable()(42);

    expect($func)->toHaveBeenCalledWith(42);
    expect($func)->not()->toHaveBeenCalledWith(12);

    fails(function () use ($func) {
        expect($func)->toHaveBeenCalledWith(12);
    });

    fails(function () use ($func) {
        expect($func)->not()->toHaveBeenCalledWith(42);
    });
});

// toHaveBeenCalledWith
it('toHaveBeenCalledWith: passes if the expectation matches any of the call made', function () {
    $func = func();

    $func->getCallable()();
    $func->getCallable()([1,2,3]);
    $func->getCallable()(42);
    $func->getCallable()(function () {
        return "";
    });

    expect($func)->toHaveBeenCalledWith([1,2,3]);
    expect($func)->not()->toHaveBeenCalledWith('');

    fails(function () use ($func) {
        expect($func)->toHaveBeenCalledWith([2,1,3]);
    });

    fails(function () use ($func) {
        expect($func)->not()->toHaveBeenCalledWith([1,2,3]);
    });
});

// toHaveBeenCalledWith
it('toHaveBeenCalledWith: pass if the expectation type matches any of the call made', function () {
    $func = func();

    $func->getCallable()();
    $func->getCallable()([1,2,3]);
    $func->getCallable()(function () {
    });
    $func->getCallable()(42, []);

    expect($func)->toHaveBeenCalledWith(ANY::ARRAY);
    expect($func)->toHaveBeenCalledWith(ANY::CALLABLE);
    expect($func)->toHaveBeenCalledWith(42, ANY::ARRAY);

    fails(function () use ($func) {
        expect($func)->not()->toHaveBeenCalledWith(ANY::CALLABLE);
        expect($func)->not()->toHaveBeenCalledWith(ANY::ARRAY);
    });
});

// toHaveBeenCalledWith
it(
    'toHaveBeenCalledWith: pass if the function has been called the expected number of times',
    function () {
        $func = func();
        $another = func();

        $func->getCallable()();
        $func->getCallable()(42);

        expect($func)->toHaveBeenCalledTimes(2);
        expect($func)->not()->toHaveBeenCalledTimes(1);

        expect($another)->toHaveBeenCalledTimes(0);
        expect($another)->not()->toHaveBeenCalledTimes(2);

        fails(function () use ($func) {
            expect($func)->not()->toHaveBeenCalledTimes(2);
        });

        fails(function () use ($func) {
            expect($func)->toHaveBeenCalledTimes(1);
        });
    }
);

// toHaveBeenCalledWith
it(
    'toHaveBeenNthCalledWith: pass if the function has been called the nth time with the expected arguments',
    function () {
        $func = func();

        $func->getCallable()(123);
        $func->getCallable()(42);

        expect($func)->toHaveBeenNthCalledWith(1, 123);
        expect($func)->toHaveBeenNthCalledWith(2, 42);
    }
);

it(
    'toHaveBeenNthCalledWith: fails if the function has been called the nth time do not match the expected arguments',
    function () {
        $func = func();

        $func->getCallable()(123);
        $func->getCallable()(42);

        fails(function () use ($func) {
            expect($func)->toHaveBeenNthCalledWith(0, 123);
        });

        fails(function () use ($func) {
            expect($func)->toHaveBeenNthCalledWith(1, 42);
        });
});

// toHaveBeenCalledBefore
it('toHaveBeenCalledBefore: pass if spy1 is called before spy2', function () {
    $spy1 = func();
    $spy2 = func();

    $spy1->getCallable()();
    $spy2->getCallable()();

    expect($spy1)->toHaveBeenCalledBefore($spy2);
    expect($spy2)->not()->toHaveBeenCalledBefore($spy1);

    fails(function () use ($spy1, $spy2) {
        expect($spy2)->toHaveBeenCalledBefore($spy1);
    });

    fails(function () use ($spy1, $spy2) {
        expect($spy1)->not()->toHaveBeenCalledBefore($spy2);
    });
});

// toHaveBeenCalledTimes
it('toHaveBeenCalledTimes: pass if the custom callback is called once', function () {
    $callback = func(function () {
        return 42;
    });

    $func = func(function () use ($callback) {
        return $callback->getCallable()();
    });

    $result = $func->getCallable()();

    expect($callback)->toHaveBeenCalledTimes(1);
    expect($result)->toBe(42);
});

// mockImplementation
it('mockImplementation: returns an instance of Func', function () {
    $mock = func()->mockImplementation(function () {
        return 42;
    });

    expect($mock)->toBeInstanceOf(Func::class);
});

// mockImplementation
it('mockImplementation: executes the custom callback', function () {
    $callback = func(function () {
        return 81;
    });

    $func = func();

    $func->mockImplementation(function () use ($callback) {
        return $callback->getCallable()();
    });

    $another = func()->mockImplementation(function () {
        return 42;
    });
    $result = $func->getCallable()();

    expect($callback)->toHaveBeenCalledTimes(1);
    expect($result)->toBe(81);
    expect($another->getCallable()())->toBe(42);
});

// mockImplementationOnce
it('mockImplementationOnce: returns an instance of Func', function () {
    $func = func();

    expect($func->mockImplementationOnce(function () {
    }))->toBeInstanceOf(Func::class);
});

it('mockImplementationOnce: runs the custom implementation only once', function () {
    $func = func(function () {
        return 42;
    });

    $func->mockImplementationOnce(function () {
        return 666;
    });

    expect($func->getCallable()())->toBe(666);
    expect($func->getCallable()())->toBe(42);
});

it('mockImplementationOnce: runs the can be chained', function () {
    $func = func(function () {
        return 42;
    });

    $func->mockImplementationOnce(function () {
        return 'one';
    })
        ->mockImplementationOnce(function () {
            return 'two';
        });

    expect($func->getCallable()())->toBe('one');
    expect($func->getCallable()())->toBe('two');
});

// mockReturnValue
it('mockReturnValue: returns an instance of Func', function () {
    $func = func()->mockReturnValue(42);

    expect($func)->toBeInstanceOf(Func::class);
});

// mockReturnValue
it('mockReturnValue: returns the specified return value', function () {
    $func = func()->mockReturnValue(42);

    expect($func->getCallable()())->toBe(42);
});

// mockReturnValueOnce
it('mockReturnValueOnce: returns an instance of Func', function () {
    $func = func()->mockReturnValueOnce(42);

    expect($func->getCallable()())->toBe(42);
});

// mockReturnValueOnce
it('mockReturnValueOnce: returns the custom value only the first time', function () {
    $func = func(function () {
        return 666;
    })->mockReturnValueOnce(42);

    $first = $func->getCallable()();
    $second = $func->getCallable()();

    expect($first)->toBe(42);
    expect($second)->toBe(666);

    $func->mockReturnValueOnce(123);

    expect($func->getCallable()())->toBe(123);
    expect($func->getCallable()())->toBe(666);
});

// mockReturnValueOnce
it('mockReturnValueOnce: can be chained', function () {
    $func = func(function () {
        return 666;
    })
        ->mockReturnValueOnce('first call')
        ->mockReturnValueOnce('second call')
        ->mockReturnValueOnce('third call');

    expect($func->getCallable()())->toBe('first call');
    expect($func->getCallable()())->toBe('second call');
    expect($func->getCallable()())->toBe('third call');
    expect($func->getCallable()())->toBe(666);
});

// mockRestore
it('mockRestore: restores the original implementation', function () {
    $func = func(function () {
        return 666;
    });

    $func->mockImplementation(function () {
        return 42;
    });

    expect($func->getCallable()())->toBe(42);

    $func->mockImplementationOnce(function () {
        return "wow";
    });
    $func->mockRestore();

    expect($func->getCallable()())->toBe(666);
});
