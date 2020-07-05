<?php

declare(strict_types=1);

namespace Verde;

use Throwable;

/**
 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
 *
 * @param mixed|callable $receivedValue the value to compare
 * @param string $customFailureMessage
 */
function expect($receivedValue, string $customFailureMessage = ''): Expect
{
    $receivedValueType = ANY::getVariableType($receivedValue);

    if (is_callable($receivedValue)) {
        try {
            $receivedValue = $receivedValue();
        } catch (Throwable $error) {
            $receivedValue = new Error(get_class($error), $error->getMessage());
        }
    }

    return new Expect($receivedValue, $receivedValueType, $customFailureMessage);
}

/**
 * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
 *
 * @param string $object the class name or function to spy on
 * @param string|null $method the class method to spy on
 *
 * @return SpyOnFunction|SpyOnClass
 */
function spyOn(string $object, string $method = null)
{
    if (is_callable($object)) {
        return SpyOnFunction::__getInstance($object);
    }

    assertNotNull($method);

    return SpyOnClass::__getInstance($object, $method ?? '');
}

function func(callable $implementation = null): Func
{
    return new Func($implementation);
}

/**
 * @param array<Mock> $mockedMethods list of custom mock methods
 */
function mock(string $className, array $mockedMethods = []): Mock
{
    return new Mock($className, $mockedMethods);
}
