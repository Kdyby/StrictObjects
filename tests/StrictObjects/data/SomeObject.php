<?php

declare(strict_types=1);

namespace KdybyTests\StrictObjects;

use Kdyby\StrictObjects\Scream;

class SomeObject
{
    use Scream;

    /** @var mixed */
    public $foo;

    /** @var mixed */
    public $bar;

    /** @var mixed */
    public static $nope;

    public function someBar() : void
    {
    }

    public function someFoo() : void
    {
    }

    public static function staFoo() : void
    {
    }

    public static function staBar() : void
    {
    }
}
