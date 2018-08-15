<?php

declare(strict_types=1);

namespace Kdyby\StrictObjects;

use Kdyby\StrictObjects\Exception\UndefinedMethod;

trait InstanceMethodCallScreamer
{
    /**
     * @param mixed[] $args arguments
     *
     * @return mixed
     *
     * @throws UndefinedMethod
     */
    public function __call(string $name, array $args)
    {
        throw UndefinedMethod::instance($this, $name);
    }
}
