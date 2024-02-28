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
     * @test
     */
    public function when()
    {
        $this->assertTrue(examine(true)->whenBoolean(fn() => true));
        $this->assertTrue(examine(1)->whenInt(fn() => true));
        $this->assertTrue(examine(1.01)->whenFloat(fn() => true));
        $this->assertTrue(examine('1234')->whenString(fn() => true));
        $this->assertTrue(examine([1, 2, 3, 4])->whenArray(fn() => true));

        // When NOT expected type, default to regular datatype
        $this->assertEquals(1234, examine(1234)->whenArray(fn () => true, 1234));
        // When NOT expected type, default to outcome callable
        $this->assertEquals(1234, examine(1234)->whenArray(fn () => true, fn() => 1234));
    }

    /**
     * @return void
     * @test
     */
    public function whenFunction()
    {
        $bool = true;
        $this->assertTrue(when($bool, Datatype::BOOLEAN, fn () => true, fn () => false));
        $this->assertIsArray(when($bool, Datatype::BOOLEAN, fn () => [], fn () => false));

        $object = (object)[];
        $this->assertTrue(when($object, Datatype::OBJECT, fn () => true, fn () => false));
    }

    /**
     * @test
     */
    public function ignoringTypeWillReturnNull()
    {
        // NULL is nothing, will call __call and return null
        $this->assertNull(examine(null)->ignoreType()->isEmpty());

        // FLOAT is Float, will call __call, but method is not applicable to FLOAT and thus will return null
        $this->assertNull(examine(-1.01)->ignoreType()->isEmpty());
    }

    /**
     * @test
     */
    public function exceptionWhenNull()
    {
        $this->expectException(MethodNotFoundException::class);
        examine(null)->isEmpty();
    }

    /**
     * @test
     */
    public function exceptionWhenMethodNotAvailable()
    {
        $this->expectException(MethodNotFoundException::class);
        examine(123)->isWhack();
    }

    /**
     * @test
     */
    public function anotherExceptionMethodNotFound()
    {
        $this->expectException(MethodNotFoundException::class);
        examine(-1.00)->isTrue();
    }

    /**
     * @test
     */
    public function stringTests()
    {
        $this->expectException(MethodNotFoundException::class);

        $this->assertTrue(examine(123)->endsWith('123', fn () => true));
    }

    /**
     * @test
     * @throws ReflectionException|InvalidTypeException
     */
    public function object()
    {
        $object = new class extends SomeObject{ use SomeObjectTrait; };

        $this->assertTrue(examine('12345')->endsWith('45', fn () => true));

        $this->assertTrue(examine($object)->isObject());
        $this->assertTrue(examine($object)->is(Datatype::OBJECT));
        $this->assertTrue(examine($object)->hasTrait(SomeObjectTrait::class));

        $this->assertTrue(examine($object)->instanceOf(SomeObject::class));
        $this->assertTrue(examine($object)->instanceOf(SomeObjectBase::class));
        $this->assertTrue(examine($object)->hasMethod('publicStaticFunction'));
        $this->assertTrue(examine($object)->hasProperty('publicArray'));
    }

    /**
     * @test
     */
    public function booleans()
    {
        $this->assertEquals(Datatype::BOOLEAN, examine(true)->type());
        $this->assertTrue(examine(true)->isBoolean());
        $this->assertTrue(examine(true)->is(Datatype::BOOLEAN));
        $this->assertTrue(examine(true)->isTrue());
        $this->assertTrue(examine(false)->isFalse());
    }

    /**
     * @test
     */
    public function arrays()
    {
        $this->assertEquals(Datatype::ARRAY, examine([])->type());
        $this->assertTrue(examine([])->isArray());
        $this->assertTrue(examine([])->isEmpty());
        $this->assertTrue(examine([0,1,2])->isNotEmpty());
    }

    /**
     * @test
     */
    public function strings()
    {
        $this->assertEquals(Datatype::STRING, examine('asdf')->type());
        $this->assertTrue(examine('asdf')->isString());
        $this->assertTrue(examine('asdf')->isNotEmpty());
    }

    /**
     * @test
     */
    public function integers()
    {
        $this->assertEquals(Datatype::INT, examine(1)->type());
        $this->assertTrue(examine(1)->isInt());
        $this->assertTrue(examine(1)->isPositive());
        $this->assertTrue(examine(-1)->isNegative());
    }

    /**
     * @test
     */
    public function floats()
    {
        $this->assertEquals(Datatype::FLOAT, examine(1.00)->type());
        $this->assertTrue(examine(1.00)->isFloat());
        $this->assertTrue(examine(1.00)->isPositive());
        $this->assertTrue(examine(-1.00)->isNegative());
    }

    /**
     * @test
     */
    public function useWithNotExamine()
    {
        $this->assertEquals(Datatype::FLOAT, with(1.00)->type());
        $this->assertTrue(with(1.00)->isFloat());
        $this->assertTrue(with(1.00)->isPositive());
        $this->assertTrue(with(-1.00)->isNegative());
    }

    /**
     * @return void
     * @test
     */
    public function whenObjectHasTrait()
    {
        $object = new class { use SomeObjectTrait; };
        $result = examine($object)->whenHasTrait(
            SomeObjectTrait::class,
            fn () => true, // should be true
            fn () => false
        );

        $this->assertTrue($result);

        $object = new class() {};
        $result = examine($object)->whenHasTrait(
            SomeObjectTrait::class,
            fn () => true,
            fn () => false // should be false
        );

        $this->assertFalse($result);
    }

    /**
     * @return void
     * @test
     * @throws ReflectionException
     */
    public function whenObjectHasMethod()
    {
        $object = new class { public function check() {} };
        $result = examine($object)->whenHasMethod(
            'check',
            fn () => true, // should be true
            fn () => false
        );

        $this->assertTrue($result);

        $object = new class() { private function check() {} };
        $result = examine($object)->whenHasMethod(
            'check',
            fn () => true,
            fn () => false // should be false
        );

        $this->assertTrue($result);

        $object = new class() {};
        $result = examine($object)->whenHasMethod(
            'check',
            fn () => true,
            fn () => false // should be false
        );

        $this->assertFalse($result);
    }

    /**
     * @return void
     * @test
     * @throws ReflectionException
     */
    public function whenObjectHasProperty()
    {
        $object = new class { public string $check = ''; };
        $result = examine($object)->whenHasProperty(
            'check',
            fn () => true, // should be true
            fn () => false
        );

        $this->assertTrue($result);

        $object = new class() { private string $check = ''; };
        $result = examine($object)->whenHasProperty(
            'check',
            fn () => true,
            fn () => false // should be false
        );

        $this->assertTrue($result);

        $object = new class() {};
        $result = examine($object)->whenHasProperty(
            'check',
            fn () => true,
            fn () => false // should be false
        );

        $this->assertFalse($result);
    }

    /**
     * @return void
     * @test
     */
    public function booleanIsTrue()
    {
        $result = examine(true)->whenTrue(
            fn () => true,
            fn () => false
        );

        $this->assertTrue($result);

        $result = examine(false)->whenTrue(
            fn () => true,
            fn () => false
        );

        $this->assertFalse($result);
    }
}
