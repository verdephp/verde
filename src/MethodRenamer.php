<?php

declare(strict_types=1);

namespace Verde;

abstract class MethodRenamer
{
    private const RENAMED_METHOD_PREFIX = '___verde_php_';

    /**
     * @var string
     */
    protected $mockedClassName;

    /**
     * @var array<string> array of all the methods mocked
     */
    protected $mockedMethods = [];

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @var mixed the original mocked class
     */
    protected $mockObject;

    public function __construct(string $mockedClassName)
    {
        $this->mockedClassName = $mockedClassName;
        $this->mockObject = new $mockedClassName();
    }

    final protected function renameOriginalMethod(string $method): void
    {
        $normalizedMethodName = self::normalizeString($method);
        $newMethodName = self::RENAMED_METHOD_PREFIX . $normalizedMethodName;

        assertFalse(
            method_exists($this->mockedClassName, $newMethodName),
            'Class already mocked! Please restore the mock before mocking it again'
        );

        \runkit7_method_rename($this->mockedClassName, $method, $newMethodName);

        $this->mockedMethods[$method] = $newMethodName;
    }

    final protected function mockMethod(string $method): void
    {
        \runkit7_method_add(
            $this->mockedClassName,
            $method,
            '',
            '
            $mock = \Verde\Mock::getMockedMethod(
                "' . $this->mockedClassName . '",
                "' . $method . '"
            );

            $callable = method_exists($mock, "getCallable") ? $mock->getCallable() : $mock;
            return func_num_args() ? $callable(...func_get_args()) : $callable();
            '
        );
    }

    /**
     * Replaces the namespace separator with _
     *
     * @param string $string the string to normalize
     */
    final private static function normalizeString(string $string): string
    {
        $string = str_replace('\\', '_', $string);

        return strtolower($string);
    }

    final public function mockRestore(): void
    {
        foreach ($this->mockedMethods as $originalMethodName => $renamedMethodName) {
            runkit7_method_remove($this->mockedClassName, $originalMethodName);
            runkit7_method_rename($this->mockedClassName, $renamedMethodName, $originalMethodName);
        }

        $this->mockedMethods = [];
    }
}
