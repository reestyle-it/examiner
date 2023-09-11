<?php

namespace Examiner\Exceptions;

use Exception;

class MethodNotFoundException extends Exception
{

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        $message = trim(strip_tags($message));

        parent::__construct(!empty($message) ? $message : 'Method not found', $code, $previous);
    }

}
