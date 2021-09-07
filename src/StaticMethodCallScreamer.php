<?php

declare(strict_types=1);

namespace Kdyby\StrictObjects;

use Kdyby\StrictObjects\Exception\UndefinedMethod;

trait StaticMethodCallScreamer
{
    /**
     * @param mixed[] $args arguments
     *
     * @throws UndefinedMethod
     */
    public static function __callStatic(string $name, array $args): mixed
    {
        throw UndefinedMethod::static(static::class, $name);
    }
}
