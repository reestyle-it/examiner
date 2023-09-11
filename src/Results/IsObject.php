<?php

namespace Examiner\Results;

use Examiner\Enums\Datatype;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class IsObject extends IsBase
{

    protected Datatype $is = Datatype::OBJECT;

    protected static array $references = [];

    public function instanceOf(string $class): bool
    {
        return $this->thing instanceof $class;
    }

    /**
     * @throws ReflectionException
     */
    public function hasTrait(string $trait): bool
    {
        $reference = $this->getReference();

        if (!isset(static::$references[$reference]['traits'])) {
            $traits = [];

            $reflectionTraits = (new ReflectionClass($this->thing))->getTraits();
            each($reflectionTraits, function (ReflectionClass $val) use (&$traits) {
                $traits[] = $val->getName();
            });
            static::$references[$reference]['traits'] = $traits;
        }

        return examine(static::$references[$reference]['traits'])->has($trait);
    }

    public function hasProperty(string $property): bool
    {
        return property_exists($this->thing, $property);
    }

    /**
     * @throws ReflectionException
     */
    public function hasMethod(string $method): bool
    {
        return examine($this->collectMethods('methods'))->hasValue($method);
    }

    public function hasStaticMethod(string $method): bool
    {
        return in_array($method, $this->collectMethods('static_methods', ReflectionMethod::IS_PUBLIC));
    }

    public function hasPublicMethod(string $method): bool
    {
        return in_array($method, $this->collectMethods('public_methods', ReflectionMethod::IS_PUBLIC));
    }

    private function collectMethods(string $putIn, ?int $filter = null): array
    {
        $reference = $this->getReference();

        if (!isset(static::$references[$reference][$putIn])) {
            $reflectionMethods = (new ReflectionClass($this->thing))->getMethods($filter);
            $methods = [];
            each($reflectionMethods, function (ReflectionMethod $val) use (&$methods) {
                $methods[] = $val->getName();
            });
            static::$references[$reference][$putIn] = $methods;
        }

        return static::$references[$reference][$putIn];
    }

    public function getReference(): string
    {
        return spl_object_hash($this->thing);
    }
}
