<?php

declare(strict_types=1);

namespace KdybyTests\StrictObjects;

use Kdyby\StrictObjects\Scream;

class SomeObject
{
    use Scream;

    public mixed $foo;

    public mixed $bar;

    public static mixed $nope;

    public function someBar(): void
    {
    }

    public function someFoo(): void
    {
    }

    public static function staFoo(): void
    {
    }

    public static function staBar(): void
    {
    }
}
