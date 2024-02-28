<?php

namespace Examiner\Results;

use Examiner\Enums\Datatype;
use Examiner\Results\Traits\Number;

class IsFloat extends IsBase
{

    use Number;

    protected Datatype $is = Datatype::FLOAT;

    public function whenPositive(Callable $whenTrue, Callable $whenFalse)
    {
        return $this->__doCall($this->isPositive(), $whenTrue, $whenFalse);
    }

    public function whenNegative(Callable $whenTrue, Callable $whenFalse)
    {
        return $this->__doCall($this->isNegative(), $whenTrue, $whenFalse);
    }

    public function whenEqual(float $to, Callable $whenTrue, Callable $whenFalse)
    {
        return $this->__doCall($this->thing === $to, $whenTrue, $whenFalse);
    }

    public function whenLargerThan(float $to, Callable $whenTrue, Callable $whenFalse)
    {
        return $this->__doCall($this->thing > $to, $whenTrue, $whenFalse);
    }

    public function whenLargerOrEqualTo(float $to, Callable $whenTrue, Callable $whenFalse)
    {
        return $this->__doCall($this->thing >= $to, $whenTrue, $whenFalse);
    }

    public function whenSmallerThan(float $to, Callable $whenTrue, Callable $whenFalse)
    {
        return $this->__doCall($this->thing < $to, $whenTrue, $whenFalse);
    }

    public function whenSmallerOrEqualTo(float $to, Callable $whenTrue, Callable $whenFalse)
    {
        return $this->__doCall($this->thing <= $to, $whenTrue, $whenFalse);
    }

}