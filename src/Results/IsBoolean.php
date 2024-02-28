<?php

namespace Examiner\Results;

use Examiner\Enums\Datatype;

class IsBoolean extends IsBase
{

    protected Datatype $is = Datatype::BOOLEAN;

    public function isTrue(): bool
    {
        return $this->thing === true;
    }

    public function couldBeTrue(): bool
    {
        return $this->thing == true || $this->thing == 1 || !empty($this->thing) || $this->thing != null;
    }

    public function isFalse(): bool
    {
        return $this->thing === false;
    }

    public function couldBeFalse(): bool
    {
        return $this->thing == false || $this->thing == 0 || empty($this->thing) || $this->thing === null;
    }

    public function whenTrue(Callable $whenTrue, Callable $whenFalse)
    {
        return $this->__doCall($this->isTrue(), $whenTrue, $whenFalse);
    }
}