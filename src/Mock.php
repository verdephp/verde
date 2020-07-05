<?php

declare(strict_types=1);

namespace Verde;

final class Mock extends MethodRenamer
{
    /**
     * @var array<Mock> list of mocks created so far
     */
    protected static $mocks;

    /**
     * Mock constructor.
     *
     * @param string $className the class name to mock
     * @param array<Mock> $mockedMethods array of custom mock methods
     */
    public function __construct(string $className, array $mockedMethods)
    {
        parent::__construct($className);

        $this->replaceMethodsWithMockedOnce($mockedMethods);
    }

    /**
     * @param array<Mock> $mockedMethods
     */
    private function replaceMethodsWithMockedOnce(array $mockedMethods): void
    {
        $methods = get_class_methods($this->mockedClassName);
        assertNotEmpty($methods, 'No methods found');

        $class = $this->mockedClassName;
        foreach ($methods as $method) {
            $this->renameOriginalMethod($method);

            self::$mocks[$class][$method] = $mockedMethods[$method] ?? func();

            $this->mockMethod($method);
        }
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @return mixed the original class
     */
    public function getMock()
    {
        return $this->mockObject;
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @return mixed the original or mocked method
     */
    public function getMockedMethod(string $className, string $method)
    {
        $mock = self::$mocks[$className][$method] ?? null;

        assertNotNull(
            $mock,
            sprintf('undefined mock for ClassName/Method %s/%s', $className, $method)
        );

        return self::$mocks[$className][$method];
    }
}
