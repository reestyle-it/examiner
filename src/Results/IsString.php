<?php

namespace Examiner\Results;

use Examiner\Enums\Datatype;

class IsString extends IsBase
{

    protected Datatype $is = Datatype::STRING;

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    public function isEmpty(): bool
    {
        return strlen(trim(strip_tags($this->thing))) === 0;
    }
}