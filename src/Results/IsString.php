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

    public function contains(string $part): mixed
    {
        return str_contains($this->thing, $part);
    }

    public function whenEmpty(Callable $whenTrue, Callable $whenFalse)
    {
        return $this->__doCall($this->isEmpty(), $whenTrue, $whenFalse);
    }

    public function whenNotEmpty(Callable $whenTrue, Callable $whenFalse)
    {
        return $this->__doCall($this->isNotEmpty(), $whenTrue, $whenFalse);
    }

    public function whenStartsWith(string $startsWith, Callable $whenTrue, Callable $whenFalse)
    {
        return $this->__doCall($this->startsWith($startsWith), $whenTrue, $whenFalse);
    }

    public function whenEndsWith(string $endsWith, Callable $whenTrue, Callable $whenFalse)
    {
        return $this->__doCall($this->endsWith($endsWith), $whenTrue, $whenFalse);
    }

    public function whenContains(string $part, Callable $whenTrue, Callable $whenFalse)
    {
        return $this->__doCall($this->contains($part), $whenTrue, $whenFalse);
    }
}