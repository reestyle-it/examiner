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

    protected $ignoreType = false;

    protected Datatype $is = Datatype::UNSET;

    public function __construct(
        public mixed $thing = null
    )
    {
        $this->validTypes = [
            Datatype::ARRAY,
            Datatype::BOOLEAN,
            Datatype::FLOAT,
            Datatype::INT,
            Datatype::OBJECT,
            Datatype::STRING,
        ];
    }

    public function ignoreType(): self
    {
        $this->ignoreType = true;

        return $this;
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
     * @throws InvalidTypeException
     */
    public function __call($var, $args)
    {
        static $types = [];

        if (count($types) === 0) {
            each($this->validTypes, function (Datatype $val) use (&$types) {
                $types[] = $val->value;
            });
        }

        $matches = [];
        preg_match('/^(?<startsWith>is|when)(?<type>(' . implode('|', $types) . '))$/i', $var, $matches);
        $type = strtolower($matches['type']);
        $startsWith = $matches['startsWith'];

        $typeExists = in_array($type, $types);

        // isArray(), isString(), etc...
        if ($startsWith === 'is' && $typeExists) {
            $type = Datatype::from($type);

            return $this->handleIs($type);
        }

        // isArray(), isString(), etc...
        if ($startsWith === 'when' && $typeExists) {
            return $this->handleWhen($type, $args);
        }

        if (! $this->ignoreType) {
            throw new MethodNotFoundException($var);
        }

        return null;
    }

    /**
     * @throws InvalidTypeException
     */
    private function handleIs(Datatype $type): bool
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

    protected function __doCall(bool $test, Callable $whenTrue, Callable $whenFalse): mixed
    {
        return call_user_func($test ? $whenTrue : $whenFalse);
    }
}
