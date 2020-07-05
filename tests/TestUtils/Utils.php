<?php

// Namespace doesn't match PSR-4 because we don't want to automatically
// load this class
// via composer :)
namespace Verde\Test;

use Countable;
use Exception;
use PHPUnit\Framework\ExpectationFailedException;

function fails($callback)
{
    $has_thrown = false;

    try {
        $callback();
    } catch (ExpectationFailedException $e) {
        $has_thrown = true;
    }

    assertTrue($has_thrown);
}

class DummyClass implements Countable
{
    public function doSomething()
    {
        return 42;
    }

    protected function doIT()
    {
        return 123;
    }

    public function count(): int
    {
        return count(get_class_methods(self::class));
    }
}

class MyException extends Exception
{
}

class AnotherDummy extends DummyClass
{
    public function test()
    {
        return $this->doIT();
    }

    public function testMock(DummyClass $dummyClass): int
    {
        return $dummyClass->doSomething();
    }

    public function throwError()
    {
        throw new MyException("Please try again");
    }
}

function testSpy()
{
}

function dummyTest(...$what)
{
    return func_num_args() > 0 ? join(', ', $what) : 42;
}

function doSomething()
{
    $value = dummyTest();

    print_r($value);
}
