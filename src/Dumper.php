<?php

namespace Examiner;

class Dumper
{

    public static function dump(... $these)
    {
        call_user_func_array('var_dump', func_get_args());
    }

    public static function dd(... $these)
    {
        self::dump(... $these);
        exit;
    }

}