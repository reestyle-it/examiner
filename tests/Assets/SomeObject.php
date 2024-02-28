<?php

namespace Test\Assets;

class SomeObject extends SomeObjectBase
{

    public string $publicString = 'asdf';
    public bool $publicBool = true;
    public int $publicInt = 99;
    public float $publicFloat = 99.9;
    public array $publicArray = ['asdf', true, 99, 99.9];

    public static function publicStaticFunction()
    {

    }

    public function publicFunction()
    {

    }

}