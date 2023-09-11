<?php

namespace Examiner\Results;

use Examiner\Enums\Datatype;
use Examiner\Results\Traits\Number;

class IsFloat extends IsBase
{

    use Number;

    protected Datatype $is = Datatype::FLOAT;

}