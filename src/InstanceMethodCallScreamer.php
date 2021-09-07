<?php

declare(strict_types=1);

namespace Kdyby\StrictObjects;

use Kdyby\StrictObjects\Exception\UndefinedMethod;

trait InstanceMethodCallScreamer
{
    /**
     * @param mixed[] $args arguments
     *
     * @throws UndefinedMethod
     */
    public function __call(string $name, array $args): mixed
    {
        throw UndefinedMethod::instance($this, $name);
    }
}
