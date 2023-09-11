<?php

namespace Examiner\Results;

use Examiner\Enums\Datatype;
use Examiner\Results\Traits\Number;

class IsInt extends IsBase
{

    use Number;

    protected Datatype $is = Datatype::INT;
}