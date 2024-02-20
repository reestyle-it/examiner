<?php

namespace Examiner\Results;

use Examiner\Enums\Datatype;

class IsArray extends IsBase
{

    protected Datatype $is = Datatype::ARRAY;

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    public function isEmpty(): bool
    {
        return count($this->thing) === 0;
    }

    public function hasValue(mixed $value, bool $strict = false): bool
    {
        return array_search($value, $this->thing, $strict) !== false;
    }

    public function hasKey(int|string $key): bool
    {
        return array_key_exists($key, $this->thing);
    }
}