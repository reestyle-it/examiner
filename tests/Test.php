<?php

namespace Test;

use PHPUnit\Framework\TestCase;

/**
 * @method bool assertTrue($actual, $message = '')
 * @method bool assertFalse($actual, $message = '')
 * @method bool assertEquals($expected, $actual, $message = '')
 * @method bool assertContains($needle, array $haystack, $message = '')
 * @method bool assertStringStartsWith($expected, $actual, $message = '')
 * @method bool expectException($exception, $message = '')
 */
class Test extends TestCase
{

    private mixed $oldHandler = null;

    public function endException()
    {

    }

}