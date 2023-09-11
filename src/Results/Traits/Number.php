<?php

namespace Examiner\Results\Traits;

/**
 * @property mixed $thing
 */
trait Number
{

    public function isPositive(): bool
    {
        return $this->thing >= 0;
    }

    public function isNegative(): bool
    {
        return $this->thing < 0;
    }
}