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

    public function startsWith(string $endsWith, mixed $default = null): mixed
    {
        $return = str_starts_with($this->thing, $endsWith);

        if (!$return) {
            $return = $this->returnAlternate($default);
        }

        return $return;
    }

    public function endsWith(string $endsWith, mixed $default = null): mixed
    {
        $return = str_ends_with($this->thing, $endsWith);

        if (!$return) {
            $return = $this->returnAlternate($default);
        }

        return $return;
    }
}