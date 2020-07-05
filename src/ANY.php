<?php

declare(strict_types=1);

namespace Verde;

abstract class ANY
{
    private const CUSTOM_PREFIX = '___verde-type__';

    public const ARRAY = self::CUSTOM_PREFIX . 'array';
    public const BOOL = self::CUSTOM_PREFIX . 'bool';
    public const CALLABLE = self::CUSTOM_PREFIX . 'callable';
    public const COUNTABLE = self::CUSTOM_PREFIX . 'countable';
    public const FLOAT = self::CUSTOM_PREFIX . 'float';
    public const INFINITE = self::CUSTOM_PREFIX . 'infinite';
    public const INT = self::CUSTOM_PREFIX . 'int';
    public const ITERABLE = self::CUSTOM_PREFIX . 'iterable';
    public const NUMERIC = self::CUSTOM_PREFIX . 'numeric';
    public const OBJECT = self::CUSTOM_PREFIX . 'object';
    public const RESOURCE = self::CUSTOM_PREFIX . 'resource';
    public const STRING = self::CUSTOM_PREFIX . 'string';

    /**
     * @var array[string]
     */
    private const PHP_IS_FUNCTIONS = [
        'is_array',
        'is_bool',
        'is_callable',
        'is_countable',
        'is_infinite',
        'is_float',
        'is_int',
        'is_iterable',
        'is_numeric',
        'is_object',
        'is_resource',
        'is_string',
    ];

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param mixed $value the value to check
     */
    final public static function isAnyType($value): bool
    {
        return is_string($value) && strpos($value, self::CUSTOM_PREFIX) === 0;
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param mixed $value the value to compare
     *
     * @return mixed the value stripped by CUSTOM_PREFIX or the original one
     */
    final public static function stripAnyCustomPrefix($value)
    {
        return is_string($value) && strpos($value, self::CUSTOM_PREFIX) !== false
            ? str_replace(self::CUSTOM_PREFIX, '__', $value) . '__'
            : $value;
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param mixed $value the value to retrieve its type
     *
     * @return array<string>
     */
    final public static function getVariableType($value): array
    {
        $types = [];

        foreach (self::PHP_IS_FUNCTIONS as $php_is_function) {
            try {
                $result = call_user_func($php_is_function, $value);

                if ($result === true) {
                    $any_type = substr($php_is_function, 3);
                    $types[] = constant("\Verde\ANY::" . strtoupper($any_type));
                }
            } catch (\Throwable $ignore) {
                // @ignoreException
                // some is_{...} might throw an error because of the type we're trying
                // to compare, but we don't care about those here :).
            }
        }

        return $types;
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param mixed $actual the value to compare
     * @param mixed $expected the value expected
     */
    final public static function compare($actual, $expected): bool
    {
        if (ANY::isAnyType($expected)) {
            $actualType = self::getVariableType($actual);

            return in_array($expected, $actualType, true);
        }

        return $actual === $expected;
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint
     *
     * @param mixed $value the value to map
     *
     * @return mixed the original value or __CALLABLE__ for callable items
     */
    final public static function mapFunctionToFunctionString($value)
    {
        if (array_key_exists(0, $value) && is_callable($value[0])) {
            return '__CALLABLE__';
        }

        return $value;
    }
}
