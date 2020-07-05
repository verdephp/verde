<?php

declare(strict_types=1);

namespace Verde;

abstract class Assert
{
    private const PESTO_BASH_DEFAULT = "\e[0m";
    private const PESTO_BASH_GREEN = "\e[32m";
    private const PESTO_BASH_RED = "\e[31m";

    /** @var string */
    private $assertFailurePrefix = '';

    /**
     * @var bool
     */
    protected $assertAgainst = true;

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @var mixed $receivedValue the value to compare
     */
    protected $receivedValue = null;

    /**
     * @var string|null
     */
    private $customFailureMessage;

    /**
     * @var array<mixed>
     */
    protected $receivedValueType;

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param mixed $receivedValue the value to compare
     * @param array<string> $receivedValueType the value type
     * @param string $customFailureMessage the custom message to show in case the assert fails
     */
    public function __construct(
        $receivedValue,
        array $receivedValueType,
        string $customFailureMessage
    ) {
        $this->receivedValue = $receivedValue;
        $this->receivedValueType = $receivedValueType;
        $this->customFailureMessage = $customFailureMessage;
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param bool $condition condition to assert
     * @param mixed $expectedValue the expected value for the "Expected" debug message
     * @param mixed $customReceivedValue debug value to print in case the assert fails
     */
    final protected function assert(
        bool $condition,
        $expectedValue,
        $customReceivedValue = null
    ): void {
        $trace = debug_backtrace();
        $caller = $trace[1]['function'];

        // NOTE: call_user_func works but will prevent pest
        // from showing which line in the test did actually fail
        $assertFailureMessage = $this->customFailureMessage .
            PHP_EOL .
            $this->generateExpectMessage(
                $caller,
                $expectedValue,
                $customReceivedValue ?? $this->receivedValue
            );

        assertTrue($condition === $this->assertAgainst, $assertFailureMessage);
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param string $caller the expect method caller
     * @param mixed $expectedValue the expect value
     * @param mixed $receivedValue the received value
     */
    final private function generateExpectMessage(
        string $caller,
        $expectedValue,
        $receivedValue
    ): string {
        $callerWithPrefix = $this->assertFailurePrefix . $caller;
        $expectedValue = $this->convertToString($expectedValue);
        $receivedValue = $this->convertToString($receivedValue);

        $greenColor = self::PESTO_BASH_GREEN;
        $redColor = self::PESTO_BASH_RED;
        $resetColor = self::PESTO_BASH_DEFAULT;

        return "
expected(${redColor}received${resetColor})->${callerWithPrefix}(${greenColor}expected${resetColor});

- Expected: ${greenColor}${expectedValue}${resetColor}
- Received: ${redColor}${receivedValue}${resetColor}
        ";
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param mixed $variable the value to make readable
     *
     * @return mixed
     */
    final protected function getType($variable)
    {
        $type = gettype($variable);

        if (is_object($variable)) {
            $objectID = spl_object_id($variable);
            $class = get_class($variable);

            return "${class} #${objectID}";
        }

        return $type;
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param mixed $value the value to make readable
     */
    final private function convertToString($value): string
    {
        if (is_object($value)) {
            return basename(get_class($value));
        }
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return print_r($value, true);
    }

    final protected function updateAssertAgainst(bool $against, string $failurePrefix): void
    {
        $this->assertAgainst = $against;
        $this->assertFailurePrefix = $failurePrefix;
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param array<mixed> $calledArguments called arguments to check
     * @param array<mixed> $expectedArguments called arguments to compare with
     *
     * @return array<mixed> list of all the arguments that matches the expectation
     */
    final protected function compareArguments(
        array $calledArguments,
        array $expectedArguments
    ): array {
        return array_filter(
            $calledArguments,
            static function ($call, $index) use ($expectedArguments): bool {
                return array_key_exists($index, $expectedArguments)
                    && ANY::compare($call, $expectedArguments[$index]);
            },
            ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * In case of failure we don't want the received/expected output to print
     * a "function/closure" output because is very long and not readable.
     * So, we're going to replace those with the "FUNCTION" string.
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param array<mixed> $calls the mock function called arguments
     */
    final protected function mapPHPClosureOutputForDebug(array $calls): string
    {
        $mapped = array_map(['Verde\ANY', 'mapFunctionToFunctionString'], $calls);

        return print_r($mapped, true);
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param array<mixed> $data the arguments to verify
     *
     * @return array<mixed> the arguments stripped of ___verde-type__
     */
    final protected function stripCustomAnyPrefixFromString(array $data): array
    {
        return array_map(['Verde\ANY', 'stripAnyCustomPrefix'], $data);
    }
}
