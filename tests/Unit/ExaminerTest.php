<?php

namespace Test\Unit;

use Examiner\Enums\Datatype;
use Examiner\Exceptions\InvalidTypeException;
use Examiner\Exceptions\MethodNotFoundException;
use ReflectionException;
use Test\Assets\SomeObject;
use Test\Assets\SomeObjectBase;
use Test\Assets\SomeObjectTrait;
use Test\Test;

class ExaminerTest extends Test
{

    /**
     * @throws ReflectionException
     * @throws InvalidTypeException
     */
    public function testExamine()
    {
        $object = new SomeObject();

        $this->assertTrue(examine(true)->whenBoolean(fn () => true));
        $this->assertTrue(examine(1)->whenInt(fn () => true));
        $this->assertTrue(examine(1.01)->whenFloat(fn () => true));
        $this->assertTrue(examine('1234')->whenString(fn () => true));
        $this->assertTrue(examine([1,2,3,4])->whenArray(fn () => true));

        $this->expectException(MethodNotFoundException::class);
        examine(123)->isWhack();

        // When NOT expected type, default to regular datatype
        $this->assertEquals(1234, examine(1234)->whenArray(fn () => true, 1234));
        // When NOT expected type, default to outcome callable
        $this->assertEquals(1234, examine(1234)->whenArray(fn () => true, fn() => 1234));

        $this->assertTrue(examine('12345')->endsWith('45', fn () => true));

        $this->expectException(MethodNotFoundException::class);
        $this->assertTrue(examine(123)->endsWith('123', fn () => true));

        $this->assertTrue(examine($object)->isObject());
        $this->assertTrue(examine($object)->is(Datatype::OBJECT));
        $this->assertTrue(examine($object)->hasTrait(SomeObjectTrait::class));
        $this->assertTrue(examine($object)->instanceOf(SomeObject::class));
        $this->assertTrue(examine($object)->instanceOf(SomeObjectBase::class));
        $this->assertTrue(examine($object)->hasMethod('publicStaticFunction'));
        $this->assertTrue(examine($object)->hasProperty('publicArray'));

        $this->assertEquals(Datatype::BOOLEAN, examine(true)->type());
        $this->assertTrue(examine(true)->isBoolean());
        $this->assertTrue(examine(true)->is(Datatype::BOOLEAN));
        $this->assertTrue(examine(true)->isTrue());
        $this->assertTrue(examine(false)->isFalse());

        $this->assertEquals(Datatype::ARRAY, examine([])->type());
        $this->assertTrue(examine([])->isEmpty());
        $this->assertTrue(examine([0,1,2])->isNotEmpty());

        $this->assertEquals(Datatype::STRING, examine('asdf')->type());
        $this->assertTrue(examine('asdf')->isNotEmpty());

        $this->assertEquals(Datatype::INT, examine(1)->type());
        $this->assertTrue(examine(1)->isPositive());
        $this->assertTrue(examine(-1)->isNegative());

        $this->assertEquals(Datatype::FLOAT, examine(1.00)->type());
        $this->assertTrue(examine(1.00)->isPositive());
        $this->assertTrue(examine(-1.00)->isNegative());

        $this->expectException(MethodNotFoundException::class);
        examine(-1.00)->isTrue();
    }
}
