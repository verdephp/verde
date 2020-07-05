<?php

declare(strict_types=1);

namespace Verde;

class Func extends Implementation
{
    /**
     *  @var array<Func|callable> $mocks array of all the mocks created
     */
    protected static $mocks = [];

    /**
     * @var string|null the internal $mock key
     */
    protected $indexKey = null;

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @return array<mixed> containing all the calls made to the mock.
     */
    final public function getCalls(): array
    {
        return $this->calls;
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @return array<mixed>|null the calls arguments for the nthTime
     */
    final public function getCall(int $index)
    {
        return $this->calls[$index] ?? null;
    }

    final public function getInvocationOrder(): int
    {
        return $this->invocationOrder;
    }

    final public function mockImplementation(callable $implementation): Func
    {
        $this->mockedImplementation = $implementation;

        return $this;
    }

    final public function mockImplementationOnce(callable $implementation): Func
    {
        $this->mockedImplementationOnce[] = $implementation;

        return $this;
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param mixed $returnValue the mock return value
     */
    final public function mockReturnValue($returnValue): Func
    {
        $this->mockedImplementation = static function () use ($returnValue) {
            return $returnValue;
        };

        return $this;
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param mixed $returnValue the mock return value
     */
    final public function mockReturnValueOnce($returnValue): Func
    {
        $this->mockImplementationOnce(static function () use ($returnValue) {
            return $returnValue;
        });

        return $this;
    }

    final public function mockClear(): void
    {
        $this->calls = [];
    }

    public function mockRestore(): void
    {
        $this->mockClear();

        $this->mockedImplementation = null;
        $this->mockedImplementationOnce = [];
    }

    final protected static function normalizeString(string $string): string
    {
        $string = str_replace('\\', '_', $string ?? '');

        return strtolower($string);
    }
}
