<?php

declare(strict_types=1);

namespace Verde;

final class Expect extends Assert
{
    public function not(): Expect
    {
        $this->updateAssertAgainst(false, 'not ->');

        return $this;
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param mixed $expectedValue the expected value
     */
    public function toBe($expectedValue): void
    {
        if (ANY::isAnyType($expectedValue)) {
            $this->assert(
                in_array($expectedValue, $this->receivedValueType, true),
                $expectedValue,
                $this->receivedValueType
            );

            return;
        }

        $this->assert($this->receivedValue === $expectedValue, $expectedValue);
    }

    public function toBeCloseTo(float $expectedValue, int $digits = 2): void
    {
        $isCloseTo = abs($expectedValue - $this->receivedValue) < pow(10, -$digits) / 2;

        $this->assert($isCloseTo, '~ ' . $expectedValue);
    }

    public function toBeCountable(): void
    {
        $this->assert(is_countable($this->receivedValue), 'true');
    }

    public function toBeFalse(): void
    {
        $this->toBe(false);
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Operators.DisallowEqualOperators
     */
    public function toBeFalsy(): void
    {
        $this->assert($this->receivedValue == false, 'false');
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param mixed $expectedValue the expected value
     */
    public function toBeGreaterThan($expectedValue): void
    {
        $this->assert($this->receivedValue > $expectedValue, ' > ' . $expectedValue);
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param mixed $expectedValue the expected value
     */
    public function toBeGreaterThanOrEqual($expectedValue): void
    {
        $this->assert($this->receivedValue >= $expectedValue, '>= ' . $expectedValue);
    }

    public function toBeTrue(): void
    {
        $this->toBe(true);
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param mixed $expectedValue the expected value
     */
    public function toBeInstanceOf($expectedValue): void
    {
        $this->assert($this->receivedValue instanceof $expectedValue, $expectedValue);
    }

    public function toBeFinite(): void
    {
        $this->assert(is_finite($this->receivedValue), 'finite');
    }

    /**
     * @param int|float $expectedValue
     */
    public function toBeLessThan($expectedValue): void
    {
        $this->assert(
            $this->receivedValue < $expectedValue,
            '< ' . $expectedValue
        );
    }

    /**
     * @param int|float $expectedValue
     */
    public function toBeLessThanOrEqual($expectedValue): void
    {
        $this->assert(
            $this->receivedValue <= $expectedValue,
            '<= ' . $expectedValue
        );
    }

    public function toBeNaN(): void
    {
        $this->assert(is_nan($this->receivedValue), 'NaN');
    }

    public function toBeNegativeInfinity(): void
    {
        $this->assert(is_infinite($this->receivedValue) && $this->receivedValue < 0, '-INF');
    }

    public function toBeNull(): void
    {
        $this->assert(is_null($this->receivedValue), 'NULL');
    }

    public function toBePositiveInfinity(): void
    {
        $this->assert(is_infinite($this->receivedValue) && $this->receivedValue > 0, '+INF');
    }

    public function toBeTruthy(): void
    {
        $this->assert($this->receivedValue == true, 'TRUE');
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param mixed $expectedValue
     */
    public function toContain($expectedValue): void
    {
        if (is_array($this->receivedValue)) {
            $this->containArrayValueOrSubset($expectedValue);
        } else {
            $this->assert(
                strpos($this->receivedValue, (string) $expectedValue) !== false,
                sprintf('"%s" to contain "%s"', $this->receivedValue, $expectedValue)
            );
        }
    }

    public function toCount(int $total): void
    {
        $count = is_countable($this->receivedValue) ? count($this->receivedValue) : 0;

        $this->assert($count === $total, $total, $count);
    }

    public function toHaveBeenCalled(): void
    {
        $calls = $this->receivedValue->getCalls();
        $expectedValue = $this->assertAgainst ? '> 1' : 0;

        $customReceivedValue = $this->mapPHPClosureOutputForDebug($calls);

        $this->assert(
            (is_countable($calls) ? count($calls) : 0) >= 1,
            $expectedValue,
            $customReceivedValue
        );
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param array<mixed> $expectedArguments expected arguments for the mock call
     */
    public function toHaveBeenCalledWith(...$expectedArguments): void
    {
        $calls = $this->receivedValue->getCalls();

        $matchingCalls = array_filter(
            $calls,
            function ($calledArguments) use ($expectedArguments): bool {
                $matchingArguments = $this->compareArguments(
                    $calledArguments,
                    $expectedArguments
                );

                return count($matchingArguments) === count($expectedArguments);
            }
        );

        $customReceivedValue = $this->mapPHPClosureOutputForDebug($calls);
        $strippedExpectedArguments = $this->stripCustomAnyPrefixFromString($expectedArguments);

        $this->assert(
            ($matchingCalls === null ? 0 : count($matchingCalls)) !== 0,
            $strippedExpectedArguments,
            $customReceivedValue
        );
    }

    public function toHaveBeenCalledBefore(Func $expected): void
    {
        $mock1Order = $this->receivedValue->getInvocationOrder();
        $mock2Order = $expected->getInvocationOrder();

        $expectedMessageValue = [
            $this->getType($expected),
            $this->getType($this->receivedValue),
        ];

        $receivedMessage = [];
        $receivedMessage[$mock1Order] = $this->getType($this->receivedValue);
        $receivedMessage[$mock2Order] = $this->getType($expected);

        $this->assert($mock1Order < $mock2Order, $expectedMessageValue, $receivedMessage);
    }

    public function toHaveBeenCalledTimes(int $times): void
    {
        $calls = $this->receivedValue->getCalls();
        $timesCalled = is_countable($calls) ? count($calls) : 0;

        $this->assert(
            (is_countable($calls) ? count($calls) : 0) === $times,
            $times,
            (string) $timesCalled
        );
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param array<mixed> $expectedArguments
     */
    public function toHaveBeenNthCalledWith(int $nthTime, ...$expectedArguments): void
    {
        $calledArguments = $calls = $this->receivedValue->getCall($nthTime - 1);

        $this->assert(
            $calledArguments === $expectedArguments,
            $expectedArguments,
            $calledArguments
        );
    }

    public function toHaveLength(int $length): void
    {
        $expected_length = is_array($this->receivedValue)
            ? count($this->receivedValue) : strlen($this->receivedValue);

        $this->assert($expected_length === $length, $length);
    }

    public function toHaveMethod(string $method): void
    {
        $this->assert(method_exists($this->receivedValue, $method), $method);
    }

    public function toThrow(string $error = ''): void
    {
        $this->toBeInstanceOf(Error::class);

        if ($error !== '') {
            $receivedValue = $this->receivedValue->getMessage();

            $this->assert($receivedValue === $error, $error, $receivedValue);
        }
    }

    public function toThrowError(string $errorClass, string $errorMessage = ''): void
    {
        $this->toBeInstanceOf(Error::class);

        $receivedErrorClass = $this->receivedValue->getErrorClass();
        $receivedErrorMessage = $this->receivedValue->getMessage();

        $this->assert($receivedErrorClass === $errorClass, $errorClass, $receivedErrorClass);

        if ($errorMessage !== '') {
            $this->assert(
                $receivedErrorMessage === $errorMessage,
                $errorMessage,
                $receivedErrorMessage
            );
        }
    }

    /**
     * @param string|array<string> $expectedValue
     */
    public function containArrayValueOrSubset($expectedValue): void
    {
        if (! is_array($expectedValue)) {
            $expectedValue = [$expectedValue];
        }

        $this->assert(
            array_intersect(
                $expectedValue,
                $this->receivedValue
            ) === $expectedValue,
            print_r($expectedValue, true)
        );
    }
}
