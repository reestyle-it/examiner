<?php

namespace Examiner\Results;

use Examiner\Enums\Datatype;
use Examiner\Results\Traits\Number;

class IsInt extends IsBase
{

    use Number;

    protected Datatype $is = Datatype::INT;

    public function whenPositive(Callable $whenTrue, Callable $whenFalse)
    {
        return $this->__doCall($this->isPositive(), $whenTrue, $whenFalse);
    }

    public function whenNegative(Callable $whenTrue, Callable $whenFalse)
    {
        return $this->__doCall($this->isNegative(), $whenTrue, $whenFalse);
    }

    public function whenEqual(int $to, Callable $whenTrue, Callable $whenFalse)
    {
        return $this->__doCall($this->thing === $to, $whenTrue, $whenFalse);
    }

    public function whenLargerThan(int $to, Callable $whenTrue, Callable $whenFalse)
    {
        return $this->__doCall($this->thing > $to, $whenTrue, $whenFalse);
    }

    public function whenLargerOrEqualTo(int $to, Callable $whenTrue, Callable $whenFalse)
    {
        return $this->__doCall($this->thing >= $to, $whenTrue, $whenFalse);
    }

    public function whenSmallerThan(int $to, Callable $whenTrue, Callable $whenFalse)
    {
        return $this->__doCall($this->thing < $to, $whenTrue, $whenFalse);
    }

    public function whenSmallerOrEqualTo(int $to, Callable $whenTrue, Callable $whenFalse)
    {
        return $this->__doCall($this->thing <= $to, $whenTrue, $whenFalse);
    }
}