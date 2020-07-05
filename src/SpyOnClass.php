<?php

declare(strict_types=1);

namespace Verde;

final class SpyOnClass extends Func
{
    /**
     * @var string
     */
    private $originalMethodName;

    /**
     * @var string
     */
    private $renamedMethodName;

    /**
     * @var string
     */
    private $spiedClassName;

    public function __construct(string $indexKey, string $className, string $method)
    {
        $this->indexKey = $indexKey;

        $this->spyOnClassMethod($className, $method);

        parent::__construct();
    }

    public function __destruct()
    {
        unset(self::$mocks[$this->indexKey]);

        runkit_method_remove(
            $this->spiedClassName,
            $this->originalMethodName
        );

        runkit7_method_rename(
            $this->spiedClassName,
            $this->renamedMethodName,
            $this->originalMethodName
        );
    }

    public static function __getInstance(
        string $class,
        string $method
    ): SpyOnClass {
        $indexKey = sprintf(
            '%s-%s',
            self::normalizeString($class),
            self::normalizeString($method)
        );

        $mock = self::$mocks[$indexKey] ?? null;

        if ($mock === null) {
            self::$mocks[$indexKey] = new static(
                $indexKey,
                $class,
                $method
            );
        }

        return self::$mocks[$indexKey];
    }

    public function __getByKey(string $key): SpyOnClass
    {
        return self::$mocks[$key];
    }

    private function spyOnClassMethod(string $className, string $method): void
    {
        $normalizedMethodName = self::normalizeString($method);
        $newMethodName = '___verde_php_' . $normalizedMethodName;

        $this->spiedClassName = $className;
        $this->originalMethodName = $method;
        $this->renamedMethodName = $newMethodName;

        $this->renameOriginalMethod($className, $method);

        runkit_method_add(
            $className,
            $method,
            '',
            $this->getMethodSpyCode()
        );
    }

    private function renameOriginalMethod(string $className, string $method): void
    {
        assertFalse(method_exists($className, $this->renamedMethodName));

        \runkit7_method_rename($className, $method, $this->renamedMethodName);
    }

    private function getMethodSpyCode(): string
    {
        return '
        $spyKey = "' . $this->indexKey . '";
        $spy = \Verde\SpyOnClass::__getByKey($spyKey);
        $callable = $spy->getNextImplementation();

        if ($callable) {
            return func_num_args() ? $callable(...func_get_args()) : $callable();
        } else {
            $args = func_num_args() ? func_get_args() : null;

            $spy->__registerCall($args ?? []);
            return $this->' . $this->renamedMethodName . '($args);
        }
        ';
    }
}
