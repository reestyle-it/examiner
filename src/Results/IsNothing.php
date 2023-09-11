<?php

namespace Examiner\Results;

use Examiner\Enums\Datatype;

class IsNothing extends IsBase
{
protected Datatype $is = Datatype::NOTHING;
}