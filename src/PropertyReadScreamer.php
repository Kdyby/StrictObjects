<?php

declare(strict_types=1);

namespace Kdyby\StrictObjects;

use Kdyby\StrictObjects\Exception\UndefinedProperty;

trait PropertyReadScreamer
{
    /**
     * @throws UndefinedProperty
     */
    public function __get(string $name): mixed
    {
        throw UndefinedProperty::read($this, $name);
    }
}
