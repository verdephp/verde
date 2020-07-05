<?php

declare(strict_types=1);

namespace Verde;

abstract class Implementation
{
    /**
     * @var callable|null $originalImplementation the original implementation
     */
    protected $originalImplementation = null;

    /**
     * @var int invocationOrder which order this mock has been called
     */
    protected $invocationOrder = null;

    /**
     * @var int $nextInvocationOrder order which the mocks are called
     */
    private static $nextInvocationOrder = 0;

    /**
     * @var callable|null $mockedImplementation the mock implementation
     */
    protected $mockedImplementation;

    /**
     * @var array<Func|callable>
     */
    protected $mockedImplementationOnce = [];

    /**
     * @var array<array> $calls used to record the mock calls
     */
    protected $calls = [];

    public function __construct(callable &$implementation = null)
    {
        $this->originalImplementation = &$implementation;
    }

    final public function getCallable(): callable
    {
        return function () {
            $num_args = func_num_args();
            $args = func_get_args();

            $result = $this->getReturnValue($num_args, $args);
            $this->invocationOrder = self::getNextInvocationOrder();

            if ($num_args === 0) {
                $args = [];
            }

            $this->__registerCall($args);

            return $result;
        };
    }

    /**
     * @return callable|\Verde\Func|null
     */
    final public function getNextImplementation()
    {
        $mockedImplementationOnce = array_shift($this->mockedImplementationOnce);
        $mockedImplementation = &$this->mockedImplementation;
        $originalImplementation = &$this->originalImplementation;

        $implementationOrder = [
            $mockedImplementationOnce,
            $mockedImplementation,
            $originalImplementation,
        ];

        $validImplementations = array_filter($implementationOrder);

        return array_shift($validImplementations);
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param array<mixed> $args
     *
     * @return mixed|null
     */
    private function getReturnValue(int $num_args, array $args)
    {
        $implementation = $this->getNextImplementation();

        if ($implementation !== null) {
            $function = Utils::getArgumentsAwareFunctionCaller($implementation);

            return $function($num_args, $args);
        }

        return null;
    }

    private static function getNextInvocationOrder(): int
    {
        self::$nextInvocationOrder += 1;

        return self::$nextInvocationOrder;
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param array<mixed> $args register the call and return
     *  information each time the mock is executed
     */
    final public function __registerCall(array $args): void
    {
        $this->calls[] = $args;
    }
}
