<?php

namespace Examiner;

use Examiner\Results\{IsBase, IsBoolean, IsObject, IsInt, IsString, IsNothing, IsFloat, IsArray};

class Examiner
{

    public function examine($thing): IsBase
    {
        switch (true) {
            case is_object($thing): return new IsObject($thing);
            case is_bool($thing): return new IsBoolean($thing);
            case is_string($thing): return new IsString($thing);
            case is_int($thing): return new IsInt($thing);
            case is_float($thing): return new IsFloat($thing);
            case is_array($thing): return new IsArray($thing);
            default: return new IsNothing();
        };
    }

}