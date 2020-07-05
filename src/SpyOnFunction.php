<?php

declare(strict_types=1);

namespace Verde;

final class SpyOnFunction extends Func
{
    /**
     * @var string
     */
    private $newFakeFunctionName;
    /**
     * @var string
     */
    private $originalFunctionName;

    public function __construct(string $indexKey, string $functionName)
    {
        $this->indexKey = $indexKey;

        $this->spyOnFunction($functionName);
        $implementation = $this->callOriginalFunction($this->newFakeFunctionName);

        parent::__construct($implementation);
    }

    public function __destruct()
    {
        unset(self::$mocks[$this->indexKey]);

        runkit7_function_remove($this->originalFunctionName);
        runkit7_function_rename($this->newFakeFunctionName, $this->originalFunctionName);
    }

    public static function __getInstance(
        string $function
    ): SpyOnFunction {
        $indexKey = self::normalizeString($function);

        $mock = self::$mocks[$indexKey] ?? null;

        if ($mock === null) {
            self::$mocks[$indexKey] = new static(
                $indexKey,
                $function
            );
        }

        return self::$mocks[$indexKey];
    }

    private function spyOnFunction(string $function): void
    {
        $normalizedFunctionName = self::normalizeString($function);
        $newFakeFunctionName = '___verde_php_' . $normalizedFunctionName;

        $this->originalFunctionName = $function;
        $this->newFakeFunctionName = $newFakeFunctionName;

        $this->renameOriginalFunction();

        runkit7_function_add(
            $function,
            '',
            $this->getFunctionSpyCode()
        );
    }

    private function renameOriginalFunction(): void
    {
        assertFalse(function_exists($this->newFakeFunctionName));

        runkit7_function_rename($this->originalFunctionName, $this->newFakeFunctionName);
    }

    private function getFunctionSpyCode(): string
    {
        return '
        $spyKey = "' . $this->indexKey . '";
        $spy = \Verde\SpyOnFunction::__getByKey($spyKey);
        $callable = $spy->getCallable();
        $fn = \Verde\Utils::getArgumentsAwareFunctionCaller($callable);

        return $fn(func_num_args(), func_get_args());
        ';
    }

    /**
     * @internal
     */
    public function __getByKey(string $key): SpyOnFunction
    {
        return self::$mocks[$key];
    }

    private function callOriginalFunction(string $originalFunctionRenamed): callable
    {
        return static function () use ($originalFunctionRenamed) {
            $function = Utils::getArgumentsAwareFunctionCaller($originalFunctionRenamed);

            return $function(
                func_num_args(),
                func_get_args()
            );
        };
    }
}
