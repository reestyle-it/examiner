<?php

use Examiner\Results\{IsBase, IsBoolean, IsString, IsArray, IsInt, IsFloat, IsObject};

if (!function_exists('examine')) {
    /**
     * @param $thing
     * @return IsBase|IsBoolean|IsString|IsArray|IsInt|IsFloat|IsObject
     */
    function examine($thing): IsBase
    {
        return (new \Examiner\Examiner)->examine($thing);
    }
}

if (!function_exists('dump')) {
    function dump(... $these): void
    {
        call_user_func_array([Examiner\Dumper::class, 'dump'], func_get_args());
    }
}

if (!function_exists('dd')) {
    function dd(... $these): void
    {
        call_user_func_array([Examiner\Dumper::class, 'dd'], func_get_args());
    }
}

if (!function_exists('each')) {
    function each(array $array, $callback): void
    {
        foreach ($array as $key => $val) {
            $callback($val, $key);
        }
    }
}