<?php

namespace Examiner\Enums;

enum Datatype: string
{

    case FLOAT = 'float';

    case STRING = 'string';

    case OBJECT = 'object';

    case BOOLEAN = 'boolean';

    case INT = 'int';

    case ARRAY = 'array';

    case NOTHING = 'nothing';

    case UNSET = 'UNSET';

}