<?php

namespace Examiner\Results;

use Examiner\Enums\Datatype;
use Examiner\Exceptions\MethodNotFoundException;

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

    protected Datatype $is = Datatype::UNSET;

    public function __construct(
        public mixed $thing = null
    )
    {
        // empty
    }

    public function type(): Datatype
    {
        return $this->is;
    }

    public function is(Datatype $is)
    {
        return $this->is === $is;
    }

    public function __call($var, $args)
    {
        $is = [
            Datatype::ARRAY->value,
            Datatype::BOOLEAN->value,
            Datatype::FLOAT->value,
            Datatype::INT->value,
            Datatype::OBJECT->value,
            Datatype::STRING->value,
        ];

        $matches = [];
        preg_match('/^(?<startsWith>is|when)(?<type>[A-Z].+)$/', $var, $matches);
        $startsWith = $matches['startsWith'];
        $type = strtolower($matches['type']);

        // isArray(), isString(), etc...
        if ($startsWith === 'is' && in_array($type, $is)) {
            return $this->type()->value === $type;
        }

        // isArray(), isString(), etc...
        if ($startsWith === 'when' && in_array($type, $is)) {
            $callBack = reset($args);
            $default = next($args);


            if (is_callable($callBack)) {
                return $this->type()->value === $type
                    && $callBack($this->thing);
            } else {
                return is_callable($default) ? $default($this->thing) : $default;
            }
        }

        throw new MethodNotFoundException();
    }
}
