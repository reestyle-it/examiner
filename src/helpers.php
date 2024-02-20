<?php

use Examiner\Enums\Datatype;
use Examiner\Exceptions\InvalidTypeException;
use Examiner\Results\{IsBase, IsBoolean, IsString, IsArray, IsInt, IsFloat, IsObject};

if (!function_exists('examine')) {
    /**
     * @param $thing
     * @return IsBase|IsBoolean|IsString|IsArray|IsInt|IsFloat|IsObject
     */
    function examine($thing): IsBase|IsBoolean|IsString|IsArray|IsInt|IsFloat|IsObject
    {
        return (new \Examiner\Examiner)->examine($thing);
    }
}

if (!function_exists('with')) {
    /**
     * Alias for examine()
     * @param $thing
     * @return IsBase|IsBoolean|IsString|IsArray|IsInt|IsFloat|IsObject
     * @see examine()
     */
    function with($thing): IsBase|IsBoolean|IsString|IsArray|IsInt|IsFloat|IsObject
    {
        return examine($thing);
    }
}

if (!function_exists('when')) {
    /**
     * Shorthand
     * @param $thing
     * @param Datatype $is
     * @param callable $trueCallback
     * @param callable|null $falseCallback
     * @return mixed
     * @throws InvalidTypeException
     * @see examine()
     */
    function when($thing, Datatype $is, Callable $trueCallback, ?Callable $falseCallback): mixed
    {
        return examine($thing)->is($is)
            ? $trueCallback($thing)
            : $falseCallback($thing);
    }
}

if (!function_exists('dump')) {
    function dump(... $these): void
    {
        call_user_func_array([Examiner\Dumper::class, 'dump'], [$these]);
    }
}

if (!function_exists('dd')) {
    function dd(... $these): void
    {
        call_user_func_array([Examiner\Dumper::class, 'dd'], [$these]);
    }
}

if (!function_exists('each')) {
    function each(array $array, $callback): void
    {
        foreach ($array as $key => $val) {
            is_callable($callback)
                ? $callback($val, $key)
                : call_user_func($callback, $val, $key);
        }
    }
}