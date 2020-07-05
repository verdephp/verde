<?php

declare(strict_types=1);

namespace Verde;

final class Utils
{
    /**
     * @param string|\Verde\Func|callable $callback
     */
    public static function getArgumentsAwareFunctionCaller($callback): callable
    {
        return static function (int $num_args, array $args) use ($callback) {
            return $num_args > 0 && is_callable($callback) ?
                call_user_func_array($callback, $args) :
                call_user_func($callback);
        };
    }
}
