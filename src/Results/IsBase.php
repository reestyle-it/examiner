<?php

namespace Examiner\Results;

use Examiner\Enums\Datatype;
use Examiner\Exceptions\InvalidTypeException;
use Examiner\Exceptions\MethodNotFoundException;
use http\Exception\InvalidArgumentException;

/**
 * @method bool isArray()
 * @method bool isBoolean()
 * @method bool isFloat()
 * @method bool isInt()
 * @method bool isObject()
 * @method bool isString()
 *
 * @method mixed whenArray(\Closure $closure, bool|\Closure $default = null)
 * @method mixed whenBoolean(\Closure $closure, bool|\Closure $default = null)
 * @method mixed whenFloat(\Closure $closure, bool|\Closure $default = null)
 * @method mixed whenInt(\Closure $closure, bool|\Closure $default = null)
 * @method mixed whenObject(\Closure $closure, bool|\Closure $default = null)
 * @method mixed whenString(\Closure $closure, bool|\Closure $default = null)
 */
abstract class IsBase
{

    public readonly array $validTypes;

    protected Datatype $is = Datatype::UNSET;

    public function __construct(
        public mixed $thing = null
    )
    {
        $this->validTypes = [
            Datatype::ARRAY->value,
            Datatype::BOOLEAN->value,
            Datatype::FLOAT->value,
            Datatype::INT->value,
            Datatype::OBJECT->value,
            Datatype::STRING->value,
        ];
    }

    public function type(): Datatype
    {
        return $this->is;
    }

    /**
     * @throws InvalidTypeException
     */
    public function is(Datatype $is)
    {
        return in_array($is, $this->validTypes) ? $this->type() === $is : throw new InvalidTypeException($is->value);
    }

    /**
     * @throws MethodNotFoundException
     */
    public function __call($var, $args)
    {
        $types = $this->validTypes;

        $matches = [];
        preg_match('/^(?<startsWith>is|when)(?<type>[A-Z].+)$/', $var, $matches);
        $startsWith = $matches['startsWith'];
        $type = strtolower($matches['type']);

        // isArray(), isString(), etc...
        if ($startsWith === 'is' && in_array($type, $types)) {
            return $this->handleIs($type);
        }

        // isArray(), isString(), etc...
        if ($startsWith === 'when' && in_array($type, $types)) {
            return $this->handleWhen($type, $args);
        }

        throw new MethodNotFoundException($var);
    }

    private function handleIs($type): bool
    {
        return $this->is($type);
    }

    private function handleWhen(string $type, array $args): mixed
    {
        $callable = reset($args);
        $default = next($args);

        $return = $default;
        if ($this->type()->value === $type) {
            if (is_callable($callable)) {
                $return = $this->type()->value === $type
                    && $callable($this->thing);
            }
        } else {
            $return = is_callable($default) ? $default($this->thing) : $default;
        }

        return $return;
    }

    protected function returnAlternate(mixed $alternate): mixed
    {
        $return = $alternate;

        if (is_callable($alternate)) {
            $return = $alternate($this->thing);
        }

        return $return;
    }
}
